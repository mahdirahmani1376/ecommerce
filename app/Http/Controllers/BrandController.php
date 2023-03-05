<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all',compact('brands'));
    }

    public function show()
    {
        return view('backend.brand.brand_add');
    }

    public function store(Request $request)
    {

        $data = $request->except('image');


        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = date('Ymdhis').'_'.$image->getClientOriginalName();
            $data['image'] = $imageName;
            $brand = Brand::create($data);
            $brand->addMedia($request->file('image'))->toMediaCollection('default');

            return $brand;
        }
        else{
            $brand = Brand::create($data);
        }


        $notification = [
            'message' => 'Brand Created Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.brand')->with($notification);
    }

    public function edit(Brand $brand) {
        return view('backend.brand.brand_edit');
    }

    public function update(Brand $brand,Request $request)
    {
        $data = $request->except('image');

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = date('Ymdhis').'_'.$image->getClientOriginalName().$image->getClientOriginalExtension();
            $data['image'] = $imageName;
        }

        $brand->update($data);

        $notification = [
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.brand')->with($notification);
    }

    public function delete(Brand $brand)
    {
        $brand->delete();
    }
}
