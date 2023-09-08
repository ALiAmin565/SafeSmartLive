<?php

namespace App\Http\Controllers;

use App\Models\telegram;
use App\Http\Resources\TelegremRsource;
use App\Http\Requests\StoretelegramRequest;
use App\Http\Requests\UpdatetelegramRequest;

class TelegramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       return TelegremRsource::collection(telegram::with('plan')->get());
    }




    public function store(StoretelegramRequest $request)
    {
        return new TelegremRsource(telegram::create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $check = telegram::find($id);

        if (!$check) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        return TelegremRsource::make($check);

    }


    public function update(StoretelegramRequest $request, $id)
    {
        $check = telegram::find($id);
        if (!$check) {
            return response()->json(['message' => 'Request not found'], 404);
        }


        $check->update($request->all());
        return response()->json(['status'=>'updated success','data'=>TelegremRsource::make($check)]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = telegram::find($id);
        if (!$check) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $check->delete();
        return response()->json(['status'=>'deleted success']);

    }
}
