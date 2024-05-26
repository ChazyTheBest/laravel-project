<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfilePaymentAttributeRequest;
use App\Http\Requests\UpdateProfilePaymentAttributeRequest;
use App\Models\ProfilePaymentAttribute;

class ProfilePaymentAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ProfilePaymentAttribute::all());
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
    public function store(StoreProfilePaymentAttributeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfilePaymentAttribute $profilePaymentAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfilePaymentAttribute $profilePaymentAttribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfilePaymentAttributeRequest $request, ProfilePaymentAttribute $profilePaymentAttribute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfilePaymentAttribute $profilePaymentAttribute)
    {
        //
    }
}
