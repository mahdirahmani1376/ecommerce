<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Response;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->get();

        return Response::json(BrandResource::collection($brands));
    }

    public function show(Brand $brand)
    {
        return Response::json(BrandResource::make($brand));
    }

    public function store(Request $request)
    {
        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('Ymdhis').'_'.$image->getClientOriginalName();
            $data['image'] = $imageName;
            $brand = Brand::create($data);
            $brand->addMedia($request->file('image'))->toMediaCollection('default');

            return $brand;
        } else {
            $brand = Brand::create($data);
        }

        $notification = [
            'message' => 'Brand Created Successfully',
            'alert-type' => 'success',
        ];

        return Response::json(BrandResource::make($brand))->withCookie($notification);
    }

    public function update(Brand $brand, Request $request)
    {
        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = date('Ymdhis').'_'.$image->getClientOriginalName().$image->getClientOriginalExtension();
            $data['image'] = $imageName;
        }

        $brand->update($data);

        $notification = [
            'message' => 'Brand Updated Successfully',
            'alert-type' => 'success',
        ];

        return Response::json(BrandResource::make($brand))->withCookie($notification);
    }

    public function delete(Brand $brand)
    {
        $brand->delete();

        return Response::json([
            'message' => 'Brand Deleted Successfully',
        ]);
    }
}
