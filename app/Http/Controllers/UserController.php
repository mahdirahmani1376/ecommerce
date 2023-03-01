<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserDashboard()
    {
        return view('user.index');
    }

    public function UserLogin()
    {
        return view('user.user_login');
    }

    public function UserDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/user/login');
    }

    public function UserProfile()
    {
        $userData = auth()->user();
        return view('user.user_profile_view',compact('userData'));
    }

    public function UserProfileStore(Request $request)
    {
        $request = $request->all();
        $user = Auth::user();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'));
            $request['photo'] = $fileName;
        }

        $user->update($request);

        $notification = [
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function UserChangePassword()
    {
        return view('user.user_change_password');
    }

    public function UserUpdatePassword(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'new_password' => 'required|confirmed',
            'old_password' => 'required',
        ]);

        if (! Hash::check($data['old_password'],$user->password))
        {
            return back()->with(['error' => 'old password does not match']);
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $user->save();

        return back()->with([
            'status' => 'password changed successfully',
        ]);

    }
}
