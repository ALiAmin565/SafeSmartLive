<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAdsRequest;
use App\Http\Requests\UpdateAdsRequest;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allAds=Ads::all();
        return response()->json([
            'data' => $allAds,
        ]);
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
     * @param  \App\Http\Requests\StoreAdsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $ads=Ads::SaveModel($request);
        return response()->json([
            'data' => 'Ads created successfully',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ads=Ads::find($id);
        return response()->json([
            'data' => $ads,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function edit(Ads $ads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdsRequest  $request
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = Ads::updateModel($request, $id);
    
        if ($result !== false) {
            return response()->json([
                'data' => 'Ads updated successfully',
            ]);
        } else {
            return response()->json([
                'error' => 'Failed to update Ads',
            ], 500); // You can choose an appropriate HTTP status code
        }
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ads  $ads
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ads=Ads::deleteModel($id);
        return response()->json([
            'data' => 'Ads deleted successfully'
        ]);
    }
}
