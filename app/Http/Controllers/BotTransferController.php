<?php

namespace App\Http\Controllers;

use App\Models\BotTransfer;
use App\Http\Controllers\Controller;
use App\Http\Resources\BotResources;
use App\Http\Requests\StoreBotTransferRequest;
use App\Http\Requests\UpdateBotTransferRequest;

class BotTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $botUsersStatus=BotTransfer::where('status', 'pending')->with(['userBotTransfer'])->orderBy('id', 'desc')->get();
        return BotResources::collection($botUsersStatus);
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
     * @param  \App\Http\Requests\StoreBotTransferRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBotTransferRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BotTransfer  $botTransfer
     * @return \Illuminate\Http\Response
     */
    public function show(BotTransfer $botTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BotTransfer  $botTransfer
     * @return \Illuminate\Http\Response
     */
    public function edit(BotTransfer $botTransfer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBotTransferRequest  $request
     * @param  \App\Models\BotTransfer  $botTransfer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBotTransferRequest $request, BotTransfer $botTransfer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BotTransfer  $botTransfer
     * @return \Illuminate\Http\Response
     */
    public function destroy(BotTransfer $botTransfer)
    {
        //
    }
}
