<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.admin_dashboard');
    }

    public function AdminDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {
        $adminData = auth()->user();
        return view('admin.admin_profile_view',compact('adminData'));
    }

    public function AdminProfileStore(Request $request)
    {
        $data = Auth::user();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'));
            $data['photo'] = $fileName;
        }

        $data->save();

        $notification = [
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function AdminChangePassword()
    {
        return view('admin.admin_change_password');
    }

    public function AdminUpdatePassword(Request $request)
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
