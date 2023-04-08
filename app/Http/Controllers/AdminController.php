<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Response::redirectToRoute('admin.login');
    }

    public function login()
    {
        return view('admin.admin_login');
    }

    public function profile()
    {
        $adminData = auth()->user();

        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function store(Request $request)
    {
        $admin = Auth::user();
        $data = $request->all();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi').$file->getClientOriginalName().$file->getClientOriginalExtension();
            $admin->addMedia($file)->toMediaCollection();
            $data['photo'] = $fileName;
        }

        $admin->update($data);

        $notification = [
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function changePassword()
    {
        return view('admin.admin_change_password');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'new_password' => 'required|confirmed',
            'old_password' => 'required',
        ]);

        if (! Hash::check($data['old_password'], $user->password)) {
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
