<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use App\Models\GenerateContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDocumentController extends Controller
{
    public function userDocument()
    {
        $id = Auth::user()->id;
        $documents = GenerateContent::where('user_id', $id)->orderBy('id', 'desc')->get();

        return view('client.backend.document.all_document', compact('documents'));
    }

    public function editUserDocument($id)
    {
        $document = GenerateContent::findOrFail($id);
        return view('client.backend.document.edit_document', compact('document'));
    }

    public function updateUserDocument(Request $request, $id)
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

        return redirect()->route('user.document')->with($notification);


    }

    
    public function deleteUserDocument(string $id)
    {
        $document = GenerateContent::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Document deleted succesfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
