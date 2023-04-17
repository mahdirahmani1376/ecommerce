<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Support\Facades\Response;
use OpenApi\Annotations as OA;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Response::json(AddressResource::collection(Address::with('addressable')->paginate()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $address = $user->addresses()->create($validated);

        return Response::json(AddressResource::make($address->load('addressable')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return Response::json(AddressResource::make($address->load('addressable')));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $validated = $request->validated();
        $user = auth()->user();
        $address->update($validated);

        $user->addresses()->update($address);

        return Response::json(AddressResource::make($address->load('addressable')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return Response::json([
            'message' => 'address removed successfully',
        ]);
    }
}
