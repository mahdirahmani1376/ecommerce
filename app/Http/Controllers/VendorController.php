<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function VendorDashboard()
    {
        return view('vendor.index');
    }

    public function VendorLogin()
    {
        return view('vendor.vendor_login');
    }

    public function VendorDestroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }

    public function VendorProfile()
    {
        $vendorData = auth()->user();
        return view('vendor.vendor_profile_view',compact('vendorData'));
    }

    public function VendorProfileStore(Request $request)
    {
        $request = $request->all();
        $user = Auth::user();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            $fileName = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'));
            $request['photo'] = $fileName;
        }

        $user->update($request);

        $notification = [
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function VendorChangePassword()
    {
        return view('vendor.vendor_change_password');
    }

    public function VendorUpdatePassword(Request $request)
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
