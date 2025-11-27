<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateInputField;
use App\Models\GenerateContent;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;


class UserTemplateController extends Controller
{
    public function userTemplate()
    {
        $user = Auth::user();
        $userPlan = $user->plan;
        $templateLimit = $userPlan ? $userPlan->templates : 3;
        $templates = Template::latest()->take($templateLimit)->get();
        return view('client.backend.template.index', compact('templates'));
    }

    public function userTemplateShow($id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        return view('client.backend.template.show', compact('template', 'user'));
    }

    public function userContentGenerate(Request $request, string $id)
    {
        // Fetch the template with its input fields
        $template = Template::with('inputFields')->findOrFail($id);

        $user = Auth::user();

        // Validate fixed fields
        $validateData = $request->validate([
            'language' => 'required|in:English (USA),Bangla (Bangladesh),Hindi (India),French (Franch),Turkish (Turkey)',
            'ai_model' => 'required|in:gpt-4,gpt-3.5-turbo',
            'result_length' => 'required|integer|min:50|max:1000',
        ]);

        // Validate dynamic input fields
        foreach ($template->inputFields as $field) {
            $fieldName = str_replace(' ', '_', $field->title);
            $request->validate([
                $fieldName => 'required|string'
            ]);
        }

        // Get user input for dynamic fields
        $inputData = $request->except('_token', 'language', 'ai_model', 'result_length');
        \Log::info('Input Data', ['inputData' => $inputData]);

        $prompt = $template->prompt;

        // Replace placeholders with user input
        foreach ($template->inputFields as $field) {
            $fieldName = str_replace(' ', '_', $field->title);
            $fieldValue = $inputData[$fieldName] ?? '';
            $prompt = str_replace('{' . str_replace(' ', '_', $field->title) . '}', $fieldValue, $prompt);
            $prompt = str_replace('{' . $field->title . '}', $fieldValue, $prompt);
        }

        // Replace {result_length} placeholder
        $prompt = str_replace('{result_length}', $validateData['result_length'], $prompt);

        // Final prompt
        $prompt = "In {$validateData['language']}, {$prompt} Aim for approximately {$validateData['result_length']} words.";
        \Log::info('Final Prompt', ['prompt' => $prompt]);

        // Word usage check
        $estimatedWordCount = $validateData['result_length'];
        $currentLimit = $user->current_word_limit ?? 100000; // default high limit if null
        $currentUsed = $user->words_used ?? 0;

        if ($currentUsed + $estimatedWordCount > $currentLimit) {
            return response()->json([
                'success' => false,
                'message' => 'Word limit exceeded',
                'words_used' => $currentUsed,
                'current_limit' => $currentLimit
            ], 400);
        }

        try {
            // Generate content with OpenAI
            $response = OpenAI::chat()->create([
                'model' => $validateData['ai_model'],
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

            $output = $response->choices[0]->message->content ?? '';
            $word_count = str_word_count(strip_tags($output));

            // Update user's word usage
            $user->current_word_usage = $user->current_word_usage - $word_count;
            $user->words_used = $currentUsed + $word_count;
            $user->save();

            // Store generated content
            GenerateContent::create([
                'user_id' => $user->id,
                'template_id' => $template->id,
                'input' => json_encode($inputData),
                'output' => $output,
                'word_count' => $word_count,
            ]);

            return response()->json([
                'success' => true,
                'output' => $output,
                'words_used' => $user->words_used,
                'current_limit' => $currentLimit
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => 'Failed to generate content: ' . $e->getMessage()
            ], 500);
        }
    }
}
