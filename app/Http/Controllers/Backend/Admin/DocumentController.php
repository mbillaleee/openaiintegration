<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\TemplateInputField;
use App\Models\GenerateContent;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\User;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminDocument()
    {
        $id = Auth::user()->id;
        $documents = GenerateContent::where('user_id', $id)->orderBy('id', 'desc')->get();

        return view('admin.backend.document.all_document', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function editAdminDocument($id)
    {
        $document = GenerateContent::findOrFail($id);

        return view('admin.backend.document.edit_document', compact('document'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function updateAdminDocument(Request $request, $id)
    {
        $document = GenerateContent::findOrFail($id);

        $validateData = $request->validate([
            'output' => 'required|string'
        ]);

        $document->update([
            'output' => $validateData['output'],
        ]);

        $notification = array(
            'message' => 'Document updated succesfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.document')->with($notification);


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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteAdminDocument(string $id)
    {
        $document = GenerateContent::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Document deleted succesfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
