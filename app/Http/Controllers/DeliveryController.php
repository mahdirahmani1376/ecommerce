<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredeliveryRequest;
use App\Http\Requests\UpdatedeliveryRequest;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoredeliveryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedeliveryRequest $request, delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(delivery $delivery)
    {
        //
    }
}
