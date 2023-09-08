<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Resources\CouponResource;
use App\Http\Requests\StorecouponRequest;
use App\Http\Requests\UpdatecouponRequest;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CouponResource::collection(Coupon::get());
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
    public function store(StorecouponRequest $request)
    {
        return response()->json(Coupon::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = Coupon::find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatecouponRequest $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $coupon->update($request->all());

        return $this->show($coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $coupon->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
