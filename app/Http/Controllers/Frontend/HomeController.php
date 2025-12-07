<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Heading;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Slider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HomeController extends Controller
{
    public function home()
    {
        return view('frontend.index');
    }
    public function homeSlider()
    {
        $slider = Slider::find(1);
        return view('admin.backend.slider.update_slider', compact('slider'));
    }

    public function updateSlider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $save_url = $slider->image; // default old image path

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            // Validate image first
            $request->validate([
                'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $manager = new ImageManager(new Driver());

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Read File Safely
            $img = $manager->read($image->getPathname());

            // Resize
            $img->resize(1696, 729);

            // Save Correct Path
            $save_path = public_path('upload/slider/' . $name_gen);
            $img->save($save_path);

            // DB URL
            $save_url = 'upload/slider/' . $name_gen;

            // Delete old image
            if (!empty($slider->image) && file_exists(public_path($slider->image))) {
                @unlink(public_path($slider->image));
            }
        }


        // Update database
        $slider->update([
            'title'       => $request->title,
            'description' => $request->description,
            'link'        => $request->link,
            'image'       => $save_url,
        ]);

        return redirect()->route('home.slider')->with([
            'message' => 'Slider updated successfully',
            'alert-type' => 'success'
        ]);
    }

    public function UpdateFrontendSliders(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update($request->only(['title', 'description']));
        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
    }

     public function UpdateSliderImage(Request $request, $id)
     {
        $slider = Slider::findOrFail($id);

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            // Validate image first
            $request->validate([
                'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $manager = new ImageManager(new Driver());

            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            // Read File Safely
            $img = $manager->read($image->getPathname());

            // Resize
            $img->resize(1696, 729);

            // Save Correct Path
            $save_path = public_path('upload/slider/' . $name_gen);
            $img->save($save_path);

            // DB URL
            $save_url = 'upload/slider/' . $name_gen;

            // Delete old image
            if (!empty($slider->image) && file_exists(public_path($slider->image))) {
                @unlink(public_path($slider->image));
            }
        }

        $slider->image = $save_url;
        $slider->save();

        return response()->json([
            'success' => true,
            'image_url' => asset($save_url),
            'message' => 'Updated successfully'
        ]);
     }

     public function allHeadings()
     {
        $heading = Heading::latest()->get();
        return view('admin.backend.heading.index', compact('heading'));
     }

     public function addHeadings()
     {
        return view('admin.backend.heading.create');
     }

     public function storeHeadings(Request $request)
     {
        $heading = new Heading();
        $heading->title = $request->title;
        $heading->description = $request->description;
        $heading->addedby_id = Auth::id();
        $heading->save();

        $notification = array(
            'message' => 'Heading created successfully',
            'alert-type' =>'success'
        );


        return redirect()->route('headings.index')->with($notification);
     }

     public function UpdateStarted(Request $request, $id)
     {
        $heading = Heading::findOrFail($id);

        $heading->update($request->only(['title', 'description']));


        return response()->json([
            'success' => true,
            'message' => 'Updated successfully'
        ]);
     }

     public function allQuestion()
     {
        $questions = Question::latest()->get();
        return view('admin.backend.question.index', compact('questions'));
     }

     public function addQuestion()
     {
        return view('admin.backend.question.create');
     }
     public function storeQuestion(Request $request)
     {
        $question = new Question();
        $question->title = $request->title;
        $question->description = $request->description;
        $question->addedby_id = Auth::id();
        $question->save();

        $notification = array(
            'message' => 'Question created successfully',
            'alert-type' =>'success'
        );

        return redirect()->route('question.index')->with($notification);
     }
     public function editQuestion($id)
     {
       $question = Question::findOrFail($id);
        return view('admin.backend.question.edit', compact('question'));
     }
    public function updateQuestion(Request $request, $id)
    {

        $question = Question::findOrFail($id);
        $question->title = $request->title;
        $question->description = $request->description;
        $question->addedby_id = Auth::id();
        $question->save();

        $notification = array(
            'message' => 'Question updated successfully',
            'alert-type' =>'success'
        );

        return redirect()->route('question.index')->with($notification);
    }
    public function deleteQuestion($id)
    {
        Question::find($id)->delete();

        $notification = array(
            'message' => 'Question deleted successfully',
            'alert-type' =>'success'
        );
        
        return redirect()->route('question.index')->with($notification);
    }

    public function homeUsecase()
    {
        return view('frontend.page.usecase');
    }

    public function homeFeature()
    {
        return view('frontend.page.feature');
    }

    public function homePriceing()
    {
        return view('frontend.page.priceing');
    }

    public function homeContact()
    {
        return view('frontend.page.contact');
    }

    public function storeContact(Request $request)
    {
        // dd($request->all());
        $message = new Contact();
        $message->name = $request->name;
        $message->email = $request->email;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();

        $notification = array(
            'message' => 'Contact message send successfully',
            'alert-type' =>'success'
        );
        
        return redirect()->back()->with($notification);
    }
}
