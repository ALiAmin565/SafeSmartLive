<?php

namespace App\Http\Controllers;

use App\Models\AffiliateCreation;
use App\Http\Requests\StoreAffiliateCreationRequest;
use App\Http\Requests\UpdateAffiliateCreationRequest;

class AffiliateCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AffiliateCreation::all();
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
     * @param  \App\Http\Requests\StoreAffiliateCreationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAffiliateCreationRequest $request)
    {
        return AffiliateCreation::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AffiliateCreation  $affiliateCreation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return AffiliateCreation::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AffiliateCreation  $affiliateCreation
     * @return \Illuminate\Http\Response
     */
    public function edit(AffiliateCreation $affiliateCreation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAffiliateCreationRequest  $request
     * @param  \App\Models\AffiliateCreation  $affiliateCreation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAffiliateCreationRequest $request, $id)
    {
        $affiliateCreation = AffiliateCreation::find($id);
        $affiliateCreation->update([
            'affiliate_code' => $request->affiliate_code ? $request->affiliate_code : $affiliateCreation->affiliate_code, 
            'affiliate_link' => $request->affiliate_link ? $request->affiliate_link : $affiliateCreation->affiliate_link,
            'name' => $request->name ? $request->name : $affiliateCreation->name,
            'status' => $request->status ? $request->status : $affiliateCreation->status,
        ]);
        return $affiliateCreation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AffiliateCreation  $affiliateCreation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $affiliateCreation = AffiliateCreation::find($id);
        $affiliateCreation->delete();
        return $affiliateCreation;
    }
}
