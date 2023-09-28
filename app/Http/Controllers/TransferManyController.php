<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\transfer_many;
use App\Http\Resources\Withdraw_moneyResource;
use App\Http\Requests\Storetransfer_manyRequest;
use App\Http\Requests\Updatetransfer_manyRequest;
use App\Http\Controllers\Deposits\WithdrwController;
use App\Http\Controllers\Helper\NotficationController;
use Illuminate\Http\JsonResponse;


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

        if (!$transferMany) {
            // Handle transfer_many record not found
            return response()->json(['error' => 'Transfer record not found.']);
        }

        $user_id = User::find($transferMany->user_id);

        if ($request['status'] == "success") {


            $withdraw = new WithdrwController();
            // $transferMany['Visa_number'] = "TKpWhCrupWsRs82PMqFbWVNPEXs5cPWNpA";
            // $transferMany['money'] = 2;
             $response = $withdraw->withdraw($transferMany['Visa_number'], $transferMany['money']);

            if (is_object($response)) {
                // Handle the object response here
                $jsonContent = $response->getContent();
                $data = json_decode($jsonContent, true);
                if (isset($data['id'])) {
                    $id = $data['id'];
                    $transferMany->status = 'success';
                    $transferMany->transaction_id_binance = $id;
                    $transferMany->save();

                    // Call notification
                    $notfication = new NotficationController();
                    $body = "تم تحويل المبلغ الى محفظتك بنجاح
                                 upvale شكرا لاستخدامك";
                    $notfication->notfication($user_id->fcm_token, $body);
                    $body = " تم خصم  الرصيد بنجاح   ";
                    $notfication->notficationManger($body);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم تحديث الحالة بنجاح.',
                    ]);
                } elseif (isset($data['msg'])) {
                    $msg = $data['msg'];

                    $notfication = new NotficationController();
                    $body = "مطلوب  $transferMany->money
                        لم تتم عملية التحويل
                        لا يوجد رصيد كافي في محفظتك يرجى الشحن في أقرب وقت ";
                    $notfication->notficationManger($body);

                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "يرجي متابعه اتعليمات ",
                    ]);
                }
            } elseif (is_array($response)) {
                // Handle the array response here
                if (isset($response['id'])) {
                    $id = $response['id'];
                    $transferMany->status = 'success';
                    $transferMany->transaction_id_binance = $id;
                    $transferMany->save();

                    // Call notification
                    $notfication = new NotficationController();
                    $body = "تم تحويل المبلغ الى محفظتك بنجاح
                             upvale شكرا لاستخدامك";
                    $notfication->notfication($user_id->fcm_token, $body);
                    $body = " تم تحويل الرصيد بنجاح شكرا لانك القائد";
                    $notfication->notficationManger($body);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم تحديث الحالة بنجاح.',
                    ]);

                    // ...
                } elseif (isset($response['msg'])) {
                    $msg = $response['msg'];

                    $notfication = new NotficationController();
                    $body = "مطلوب  $transferMany->money
                    لم تتم عملية التحويل
                    لا يوجد رصيد كافي في محفظتك يرجى الشحن في أقرب وقت ";
                    $notfication->notficationManger($body);

                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "يرجي متابعه اتعليمات ",
                    ]);
                }
            } else {
                // Handle unexpected response type here
                return 'Unexpected response type';
            }
        } elseif ($request['status'] == "declined") {
            $transferMany->status = 'declined';
            $transferMany->save();


            // Get money of user
            $usermodel = User::where('id', $transferMany->user_id)->first();
            $userMony = $usermodel->money;
            $transferMany->money;
            $total = ($userMony += $transferMany->money) + 2;

            $usermodel->update([
                'money' => $total,
            ]);


            $body = "تم رفض العمليه يرجي التواصل مع قسم الدعم";
            $notfication = new NotficationController();

            $notfication->notfication($usermodel->fcm_token, $body);

            return response()->json([
                'success' => true,
                'message' => 'Status updateddddd successfully.',
            ]);
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
