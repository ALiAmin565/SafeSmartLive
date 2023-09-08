<?php

namespace App\Http\Controllers;

use App\Models\transfer_many;
use App\Models\User;
use App\Http\Requests\Storetransfer_manyRequest;
use App\Http\Requests\Updatetransfer_manyRequest;
use App\Http\Resources\Withdraw_moneyResource;
use Illuminate\Http\Request;

class TransferManyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Withdraw_moneyResource::collection(transfer_many::where('status', 'pending')->with('user')->get());
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
     * @param  \App\Http\Requests\Storetransfer_manyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transfer_many  $transfer_many
     * @return \Illuminate\Http\Response
     */
    public function show(transfer_many $transfer_many)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transfer_many  $transfer_many
     * @return \Illuminate\Http\Response
     */
    public function edit(transfer_many $transfer_many)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Updatetransfer_manyRequest  $request
     * @param  \App\Models\transfer_many  $transfer_many
     * @return \Illuminate\Http\Response
     */
    public function update(Updatetransfer_manyRequest $request)
    {
    
        $transactionId = $request['transaction_id'];
        
       

        $transferMany = transfer_many::where('transaction_id', $transactionId)->first();
        $transferMany_mony = $transferMany->money;

        if ($transferMany) {
            if ($request['status'] == "success") {
                $transferMany->status = 'success';
                $transferMany->save();
                return response()->json([
                    'success' => 'true',
                    'message' => 'Status updated successfully.',

                ]);
            } elseif ($request['status'] == "declined") {
                $transferMany->status = 'declined';
                $transferMany->save();
                // get mony of user
                
                
                $usermodel=User::where('id',$transferMany->user_id)->first();
                
                 
                $userMony = $usermodel->money;


                $totel = $userMony + $transferMany_mony;



                $usermodel->update([
                    'money' => $totel,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.',
                ]);
                // باقي انه اغير ف جدول اليوزر

            }
        } else {
            // Handle transfer_many record not found
            return response()->json(['error' => 'Transfer record not found.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transfer_many  $transfer_many
     * @return \Illuminate\Http\Response
     */
    public function destroy(transfer_many $transfer_many)
    {
        //
    }
}
