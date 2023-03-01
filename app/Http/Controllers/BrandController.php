<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function AllBrand()
    {
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all',compact('brands'));
    }

    public function AddBrand()
    {
        return view('backend.brand.brand_add');
    }

    public function StoreBrand(Request $request)
    {
        $data = $request->all();

        $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        $saveUrl = 'upload/brand/'.$name_gen;
        $data['brand_image'] = $saveUrl;

        Brand::create($data);

        $notification = [
            'message' => 'Brand Created Successfully',
            'alert-type' => 'success',
        ];

        return redirect()->route('all.brand')->with($notification);
    }

    public function editBrand(Brand $brand) {
        return view('backend.brand.brand_edit');
    }

    public function updateBrand(Brand $brand,Request $request)
    {
        $data = $request->all();
        $brand->addMediaFromRequest($data['brand_image']);

        $brand->update($data);
    }
}
