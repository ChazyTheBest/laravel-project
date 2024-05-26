<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodAttributeRequest;
use App\Http\Requests\UpdatePaymentMethodAttributeRequest;
use App\Models\PaymentMethodAttribute;

class PaymentMethodAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(PaymentMethodAttribute::all());
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
    public function store(StorePaymentMethodAttributeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethodAttribute $paymentMethodAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethodAttribute $paymentMethodAttribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodAttributeRequest $request, PaymentMethodAttribute $paymentMethodAttribute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethodAttribute $paymentMethodAttribute)
    {
        //
    }
}
