<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateInputField;
use Illuminate\Support\Facades\Auth;

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
        //
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
                'is_required' => $field['is_required'] ?? 0,
                'order' => $index + 1,
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
}
