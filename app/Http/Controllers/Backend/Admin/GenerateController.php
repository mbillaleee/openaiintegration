<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenerateImage;
use App\Models\GeneratedAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;

class GenerateController extends Controller
{
    public function generateImage()
    {
        return view('admin.backend.generte.image_generate');
    }

    // public function GenerateAndSaveImage(Request $request)
    // {
    //     //  dd($request->all());
    //     $request->validate([
    //         'prompt' => 'required|string',
    //     ]);

    //     $prompt = $request->input('prompt');

    //     // Generate image using OpenAI
    //     $response = OpenAI::images()->create([
    //         'model' => 'dall-e-3',
    //         'prompt' => $prompt,
    //         'n' => 1,
    //         'size' => '1024x1024',
    //     ]);

       

    //     $imageUrl = $response->data[0]->url;

    //     // Step 2 : Download the image
    //     $imageContents = file_get_contents($imageUrl);
    //     $fileName = 'generated_'. time().'_'.Str::random(6).'.png';
    //     $destinationPath = public_path('upload/generated_image');

    //     // Step 3 : Ensure directory exist
    //     if (!File::exists($destinationPath)) {
    //         File::makeDirectory($destinationPath, 0755, true);
    //     }

    //     // Step 4 : Save image to public folder
    //     file_put_contents($destinationPath . '/' . $fileName, $imageContents);

    //     $generatedImage = GenerateImage::create([
    //         'user_id' => Auth::id(),
    //         'prompt'  => $prompt,
    //         'image_path' => 'upload/generated_image' . $fileName,
    //     ]);

    //     return response()->json([
    //         'status'   => 'success',
    //         'image_local_path' => asset('upload/generated_image' . $fileName),
    //         'message' => 'Image Generated and saved successfully'
    //     ]);
    // }


    public function GenerateAndSaveImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->input('prompt');

        // Generate image using OpenAI
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',   // spelling fix (dell → dall)
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',   // (1024*1024 নয়, × হবে)
        ]);

        // Get generated image URL
        $imageUrl = $response->data[0]->url;

        // Step 2: Download the image
        $imageContents = file_get_contents($imageUrl);

        // Generate unique filename
        $fileName = 'generated_' . time() . '_' . Str::random(6) . '.png';

        // Public upload path
        $destinationPath = public_path('upload/generated_image');

        // Create folder if not exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Save file to public folder
        file_put_contents($destinationPath . '/' . $fileName, $imageContents);

        // Database path (correct slash added)
        $imagePath = 'upload/generated_image/' . $fileName;

        // Save into database
        $generatedImage = GenerateImage::create([
            'user_id' => Auth::id(),
            'prompt'  => $prompt,
            'image_path' => $imagePath,
        ]);

        // Response JSON
        return response()->json([
            'status'   => 'success',
            'image_local_path' => asset($imagePath),
            'message' => 'Image generated and saved successfully',
        ]);
    }


    public function allGenerateImage()
    {
        $genImage = GenerateImage::orderBy('id', 'desc')->get();

        return view('admin.backend.generte.all_generate', compact('genImage'));
    }






    public function userGenerateImage()
    {
        return view('client.backend.generte.image_generate');
    }

     public function userGenerateAndSaveImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->input('prompt');

        // Generate image using OpenAI
        $response = OpenAI::images()->create([
            'model' => 'dall-e-3',   // spelling fix (dell → dall)
            'prompt' => $prompt,
            'n' => 1,
            'size' => '1024x1024',   // (1024*1024 নয়, × হবে)
        ]);

        // Get generated image URL
        $imageUrl = $response->data[0]->url;

        // Step 2: Download the image
        $imageContents = file_get_contents($imageUrl);

        // Generate unique filename
        $fileName = 'generated_' . time() . '_' . Str::random(6) . '.png';

        // Public upload path
        $destinationPath = public_path('upload/generated_image');

        // Create folder if not exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Save file to public folder
        file_put_contents($destinationPath . '/' . $fileName, $imageContents);

        // Database path (correct slash added)
        $imagePath = 'upload/generated_image/' . $fileName;

        // Save into database
        $generatedImage = GenerateImage::create([
            'user_id' => Auth::id(),
            'prompt'  => $prompt,
            'image_path' => $imagePath,
        ]);

        // Response JSON
        return response()->json([
            'status'   => 'success',
            'image_local_path' => asset($imagePath),
            'message' => 'Image generated and saved successfully',
        ]);
    }

    public function userAllGenerateImage()
    {
        $genImage = GenerateImage::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return view('client.backend.generte.all_generate', compact('genImage'));
    }


    public function generateAudio()
    {
        return view('admin.backend.generte.audio');
    }

    public function GenerateAndSaveAudio(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $text = $request->input('text');

        // Generate image using OpenAI
        $response = OpenAI::audio()->speech([
            'model' => 'tts-1',   // spelling fix (dell → dall)
            'input' => $text,
            'voice' => 'nova',
            'response_format' => 'mp3',   // (1024*1024 নয়, × হবে)
        ]);

        // Generate unique filename
        $fileName = 'tts_' . time() . '_' . Str::random(5) . '.mp3';

        // Public upload path
        $savePath = public_path('upload/audio/');

        // Create folder if not exists
        if (!File::exists($savePath)) {
            File::makeDirectory($savePath, 0755, true);
        }

        // Save file to public folder
        file_put_contents($savePath .  $fileName, $response);

        // Database path (correct slash added)
        $imagePath = 'upload/audio/' . $fileName;

        // Save into database
        $generatedImage = GeneratedAudio::create([
            'user_id' => Auth::id(),
            'text'  => $text,
            'audio_path' => $imagePath,
        ]);

        // Response JSON
        return response()->json([
            'status'   => 'success',
            'audio_url' => asset($imagePath),
            'message' => 'Audio generated and saved successfully',
        ]);
    }


    public function allGenerateAudio()
    {
        $genaudio = GeneratedAudio::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return view('admin.backend.generte.all_audio', compact('genaudio'));
    }

    public function userGenerateAudio()
    {
        return view('client.backend.generte.audio');
    }

    public function userGenerateAndSaveAudio(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $text = $request->input('text');

        // Generate image using OpenAI
        $response = OpenAI::audio()->speech([
            'model' => 'tts-1',   // spelling fix (dell → dall)
            'input' => $text,
            'voice' => 'nova',
            'response_format' => 'mp3',   // (1024*1024 নয়, × হবে)
        ]);

        // Generate unique filename
        $fileName = 'tts_' . time() . '_' . Str::random(5) . '.mp3';

        // Public upload path
        $savePath = public_path('upload/audio/');

        // Create folder if not exists
        if (!File::exists($savePath)) {
            File::makeDirectory($savePath, 0755, true);
        }

        // Save file to public folder
        file_put_contents($savePath .  $fileName, $response);

        // Database path (correct slash added)
        $imagePath = 'upload/audio/' . $fileName;

        // Save into database
        $generatedImage = GeneratedAudio::create([
            'user_id' => Auth::id(),
            'text'  => $text,
            'audio_path' => $imagePath,
        ]);

        // Response JSON
        return response()->json([
            'status'   => 'success',
            'audio_url' => asset($imagePath),
            'message' => 'Audio generated and saved successfully',
        ]);
    }


    public function userAllGenerateAudio()
    {
        $genaudio = GeneratedAudio::where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return view('client.backend.generte.all_audio', compact('genaudio'));
    }

}
