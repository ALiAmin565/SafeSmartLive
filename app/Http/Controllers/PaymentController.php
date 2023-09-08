<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorepaymentRequest;
use App\Http\Requests\UpdatepaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PaymentResource::collection(Payment::with('user','plan')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorepaymentRequest $request)
    {
        return response()->json(Payment::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Payment::find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepaymentRequest $request, $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $payment->update($request->all());

        return $this->show($payment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $payment->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
