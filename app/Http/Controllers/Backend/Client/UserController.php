<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userDashboard()
    {
        return view('client.index');
    }

    public function userLogout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function userProfile()
    {
        $id = Auth::id();
        $profileData = User::find($id);

        return view('client.user.profile', compact('profileData'));
    }

    
    public function userProfileUpdate(Request $request)
    {
        $id = Auth::id();
        $profileData = User::find($id);
        $profileData->name = $request->name;
        $profileData->email = $request->email;
        $profileData->phone = $request->phone;
        $profileData->address = $request->address;

        $old_photo_path = $profileData->photo;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $fileName);
            $profileData->photo = $fileName;

            if ($old_photo_path && $old_photo_path !== $fileName) {
                $this->deleteOldImage($old_photo_path);
            }
        }
        $profileData->save();

        $notification = array(
            'message' => 'User Profile updated successfully',
            'alert-type' =>'success'
        );
        return redirect()->back()->with($notification);
    }

    private function deleteOldImage(string $old_photo_path) : void
    {
        $fullPath = public_path('upload/user_images/'. $old_photo_path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    
    public function userChangePassword()
    {
        $id = Auth::id();
        $profileData = User::find($id);

        return view('client.user.password_page', compact('profileData'));
    }

    public function userPasswordUpdate(Request $request)
    {
        $id = Auth::id();
        $profileData = User::find($id);

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, $profileData->password)) {
            $notification = array(
                'message' => 'Old password does not match',
                'alert-type' =>'error'
            );
            return redirect()->back()->with($notification);
        }

        User::whereId($profileData->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        $notification = array(
            'message' => 'Password updated successfully',
            'alert-type' =>'error'
        );
        Auth::logout();
        return redirect()->route('login')->with($notification);
    }


}
