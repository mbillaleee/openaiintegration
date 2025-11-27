<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateInputField;
use App\Models\GenerateContent;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd('ok');
        $templates = Template::latest()->get();
        return view('admin.backend.template.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.backend.template.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validateData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'required|string|not_in:Select Category',
        'icon' => 'required|string',
        'prompt' => 'required|string',
        'is_active' => 'required|in:0,1',
        'input_fields' => 'required|array|min:1',
        'input_fields.*.title' => 'required|string|max:255',
        'input_fields.*.description' => 'required|string',
        'input_fields.*.type' => 'required|in:text,textarea',
    ]);

    $template = new Template();
    $template->title         = $validateData['title'];
    $template->description   = $validateData['description'];
    $template->category      = $validateData['category'];
    $template->icon          = $validateData['icon'];
    $template->prompt        = $validateData['prompt'];
    $template->is_active     = $validateData['is_active'];
    $template->created_by    = Auth::id();
    $template->save();

    // loop through input fields (in case there are more later)
    foreach ($validateData['input_fields'] as $index => $inputField) {
        TemplateInputField::create([
            'template_id'  => $template->id,
            'title'        => $inputField['title'],
            'description'  => $inputField['description'],
            'type'         => $inputField['type'],
            'is_required'  => true,
            'order'        => $index,
        ]);
    }

    return redirect()->route('templates.index')->with([
        'message' => 'Template created successfully',
        'alert-type' => 'success'
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        $user = Auth::user();
        return view('admin.backend.template.show', compact('template', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $template = Template::with('inputFields')->findOrFail($id);
        return view('admin.backend.template.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $validateData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|not_in:Select Category',
            'icon' => 'required|string',
            'prompt' => 'required|string',
            'is_active' => 'required|in:0,1',
            'input_fields' => 'required|array|min:1',
            'input_fields.*.title' => 'required|string|max:255',
            'input_fields.*.description' => 'required|string',
            'input_fields.*.type' => 'required|in:text,textarea',
        ]);

       

        $template = Template::findOrFail($id);
        $template->title         = $validateData['title'];
        $template->description   = $validateData['description'];
        $template->category      = $validateData['category'];
        $template->icon          = $validateData['icon'];
        $template->prompt        = $validateData['prompt'];
        $template->is_active     = $validateData['is_active'];
        $template->save();

         // 4️⃣ Delete old input fields (optional but clean)
        $template->inputFields()->delete();

        // 5️⃣ Recreate input fields from request
        foreach ($validateData['input_fields'] as $index => $field) {
            $template->inputFields()->create([
                'title' => $field['title'],
                'description' => $field['description'],
                'type' => $field['type'],
                'is_required' => true,
                'order' => 0,
            ]);
        }
  

        return redirect()->route('templates.index')->with([
            'message' => 'Template created successfully',
            'alert-type' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    public function adminContentGenerate(Request $request, string $id)
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



    // public function adminContentGenerate(Request $request, string $id)
    // {
    //     // 🔹 Fetch template with input fields
    //     $template = Template::with('inputFields')->findOrFail($id);
    //     $user = Auth::user();

    //     // 🔹 Validate main inputs
    //     $validatedData = $request->validate([
    //         'language' => 'required|in:English (USA),Bangladesh,Hindi (India),French (Franch),Turkish (Turkey)',
    //         'ai_model' => 'required|in:gpt-4,gpt-3.5-turbo',
    //         'result_length' => 'required|integer|min:50|max:1000',
    //     ]);

    //     // 🔹 Prepare dynamic field validation
    //     $rules = [];
    //     foreach ($template->inputFields as $field) {
    //         $fieldName = Str::slug($field->title, '_');
    //         $rules[$fieldName] = 'required|string';
    //     }

    //     // 🔹 Validate dynamic fields
    //     $validatedDynamicData = $request->validate($rules);

    //     // 🔹 Prepare prompt
    //     $prompt = $template->prompt;

    //     foreach ($template->inputFields as $field) {
    //         $fieldName = Str::slug($field->title, '_');
    //         $fieldValue = $validatedDynamicData[$fieldName] ?? '';

    //         // Replace both {Title} and {slug} placeholders
    //         $prompt = str_replace(['{' . $field->title . '}', '{' . $fieldName . '}'], $fieldValue, $prompt);
    //     }

    //     // Replace result length placeholder
    //     $prompt = str_replace('{result_length}', $validatedData['result_length'], $prompt);

    //     // Add language and result length info
    //     $prompt = "In {$validatedData['language']}, {$prompt} Aim for approximately {$validatedData['result_length']} words.";

    //     \Log::info('Final Prompt', ['prompt' => $prompt]);

    //     // 🔹 Word usage check
    //     $estimatedWordCount = $validatedData['result_length'];
    //     if (($user->words_used + $estimatedWordCount) > $user->current_word_usage) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Word limit exceeded',
    //         ], 400);
    //     }

    //     try {
    //         // 🔹 OpenAI call
    //         $response = OpenAI::chat()->create([
    //             'model' => $validatedData['ai_model'],
    //             'messages' => [
    //                 ['role' => 'user', 'content' => $prompt],
    //             ],
    //         ]);

    //         $output = $response->choices[0]->message->content ?? '';
    //         $word_count = str_word_count($output);

    //         // 🔹 Update user's word usage
    //         $user->words_used += $word_count;
    //         $user->save();

    //         // 🔹 Save generated content
    //         GenerateContent::create([
    //             'user_id' => $user->id,
    //             'template_id' => $template->id,
    //             'input' => json_encode($validatedDynamicData),
    //             'output' => $output,
    //             'word_count' => $word_count,
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'output' => $output,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'output' => 'Failed to generate content: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

//     public function adminContentGenerate(Request $request, string $id)
// {
//     // Fetch the template with its input fields
//     $template = Template::with('inputFields')->findOrFail($id);
//     $user = Auth::user();

//     // Validate main request data
//     $validateData = $request->validate([
//         'language' => 'required|in:English (USA),Bangladesh,Hindi (India),French (Franch),Turkish (Turkey)',
//         'ai_model' => 'required|string|in:gpt-4,gpt-3.5-turbo',
//         'result_length' => 'required|integer|min:50|max:1000',
//     ]);

//     // ✅ Validate dynamic input fields safely
//     foreach ($template->inputFields as $field) {
//         // Fallback: if name missing, make slug from title
//         $fieldName = $field->name ?? Str::slug($field->title, '_');

//         if (empty($fieldName)) {
//             \Log::warning('Empty field name found in template inputFields', ['field' => $field]);
//             continue;
//         }

//         $request->validate([
//             $fieldName => 'required|string',
//         ]);
//     }

//     // ✅ Get all user inputs except global fields
//     $inputData = $request->except('_token', 'language', 'ai_model', 'result_length');
//     \Log::info('Input Data', ['inputData' => $inputData]);

//     // ✅ Prepare prompt
//     $prompt = $template->prompt;

//     // Replace placeholders with user input
//     foreach ($template->inputFields as $field) {
//         $fieldName = $field->name ?? Str::slug($field->title, '_');

//         if (empty($fieldName)) continue;

//         $fieldValue = $inputData[$fieldName] ?? '';
//         $prompt = str_replace('{' . $fieldName . '}', $fieldValue, $prompt);
//         $prompt = str_replace('{' . $field->title . '}', $fieldValue, $prompt);
//     }

//     // Replace result_length placeholder if exists
//     $prompt = str_replace('{result_length}', $validateData['result_length'], $prompt);

//     // Add language and word length context
//     $prompt = "In {$validateData['language']}, {$prompt} Aim for approximately {$validateData['result_length']} words.";

//     \Log::info('Final Prompt', ['prompt' => $prompt]);

//     // ✅ Word usage validation
//     $estimatedWordCount = $validateData['result_length'];
//     if ($user->words_used + $estimatedWordCount > $user->current_word_usage) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Word limit exceeded',
//         ], 400);
//     }

//     try {
//         // ✅ OpenAI API call
//         $response = OpenAI::chat()->create([
//             'model' => $validateData['ai_model'],
//             'messages' => [
//                 ['role' => 'user', 'content' => $prompt],
//             ],
//         ]);

//         $output = $response->choices[0]->message->content ?? '';
//         $word_count = str_word_count(strip_tags($output));

//         // ✅ Update user word usage
//         $user->words_used += $word_count;
//         $user->save();

//         // ✅ Save to DB
//         GenerateContent::create([
//             'user_id' => $user->id,
//             'template_id' => $template->id,
//             'input' => json_encode($inputData),
//             'output' => $output,
//             'word_count' => $word_count,
//         ]);

//         return response()->json([
//             'success' => true,
//             'output' => $output,
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Content generation failed', ['error' => $e->getMessage()]);

//         return response()->json([
//             'success' => false,
//             'output' => 'Failed to generate content: ' . $e->getMessage(),
//         ], 500);
//     }
// }
}