<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\HistoryTransactionLogs;

class AfilliateCalculation extends Controller
{

    function afterPay($id)
    {
        $user_comming = User::find($id);
        $user_comming_affiliate = $user_comming->comming_afflite;
        if ($user_comming->plan->title != 'free') {
            $user_plan_price = $user_comming->plan->price;
            $user_master3 = User::where('affiliate_code', $user_comming_affiliate)->first();
            if (!empty($user_master3) && $user_comming_affiliate != null) {
                $user_comming_affiliate3 = $user_master3->comming_afflite;
                $perc_paln = +$user_master3->plan->percentage1;
                $this->affililateProccess($user_master3, $user_comming, $user_plan_price, $perc_paln , 'plan');
                $user_master2 = User::where('affiliate_code', $user_comming_affiliate3)->first();
                if (!empty($user_master2) && $user_comming_affiliate3 != null) {
                    $user_comming_affiliate2 = $user_master2->comming_afflite;
                    $check_his_plan = +$user_master2->plan->percentage2;
                    if ($check_his_plan != null) {
                        $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan , 'plan');
                    } else {
                        $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan = 0 , 'plan');
                    }
                    $user_master1 = User::where('affiliate_code', $user_comming_affiliate2)->first();
                    if (!empty($user_master1) && $user_comming_affiliate2 != null) {
                        $check_his_plan = +$user_master2->plan->percentage3;
                        if ($check_his_plan != null) {
                            $this->affililateProccess($user_master1, $user_comming, $user_plan_price, $check_his_plan , 'plan');
                        } else {
                            $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan = 0 , 'plan');
                        }
                    } else {
                        return "Not Assign to Father yet";
                    }
                } else {
                    return "Not Assign to Father yet";
                }
            } else {
                return "Not Assign to Father yet";
            }
        } else {
            return "Not Assign to Paln yet";
        }
    }


    function calculateFatherFees($id, $fees)
    {
        $user_comming = User::find($id);
        $user_comming_affiliate = $user_comming->comming_afflite;
        if ($user_comming->plan->title != 'free') {
            $user_plan_price = $fees;
            $user_master3 = User::where('affiliate_code', $user_comming_affiliate)->first();
            if (!empty($user_master3) && $user_comming_affiliate != null) {
                $user_comming_affiliate3 = $user_master3->comming_afflite;
                $perc_paln = +$user_master3->plan->percentage1;
                $this->affililateProccess($user_master3, $user_comming, $user_plan_price, $perc_paln, 'fees');
                $user_master2 = User::where('affiliate_code', $user_comming_affiliate3)->first();
                if (!empty($user_master2) && $user_comming_affiliate3 != null) {
                    $user_comming_affiliate2 = $user_master2->comming_afflite;
                    $check_his_plan = +$user_master2->plan->percentage2;
                    if ($check_his_plan != null) {
                        $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan, 'fees');
                    } else {
                        $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan = 0, 'fees');
                    }
                    $user_master1 = User::where('affiliate_code', $user_comming_affiliate2)->first();
                    if (!empty($user_master1) && $user_comming_affiliate2 != null) {
                        $check_his_plan = +$user_master2->plan->percentage3;
                        if ($check_his_plan != null) {
                            $this->affililateProccess($user_master1, $user_comming, $user_plan_price, $check_his_plan, 'fees');
                        } else {
                            $this->affililateProccess($user_master2, $user_comming, $user_plan_price, $check_his_plan = 0, 'fees');
                        }
                    } else {
                        return "Not Assign to Father yet";
                    }
                } else {
                    return "Not Assign to Father yet";
                }
            } else {
                return "Not Assign to Father yet";
            }
        } else {
            return "Not Assign to Paln yet";
        }
    }


    function affililateProccess($user, $userSender,  $price, $perc, $status)
    {

        $user_old_money = $user->money;
        $user_new_money = ($perc / 100) * $price;
        $user_money = $user_old_money + $user_new_money;
        $user->money = $user_money;
        $user->save();
        HistoryTransactionLogs::create([
            'id_user_sender' => $userSender->id,
            'id_user_receiver' => $user->id,
            'amount' => $user_new_money,
            'status' => $status,
        ]);
    }


    function fathers($id)
    {
        $users = [];
        $user_comming = User::find($id);
        $user_comming_affiliate = $user_comming->comming_afflite;

        if ($user_comming_affiliate) {
            for ($i = 0; $i <= 10;) {
                // return $user_comming_affiliate;
                $user_master = User::where('affiliate_code', $user_comming_affiliate)->first();
                // return  $user_master;
                if ($user_master->comming_afflite) {
                    // dd(10);
                    $users[] = $user_master->id;
                    $user_comming_affiliate = $user_master->comming_afflite;
                    // return 1;
                } else {
                    $i = 11;
                    $users[] = $user_master->id;
                }
            }
        } else {
            return 'not have children';
        }

        return $users;
    }


    function hisFirstFather($id)
    {

        $user_comming = User::find($id);
        $user_comming_affiliate = $user_comming->comming_afflite;

        if ($user_comming_affiliate) {
            $user_master = User::where('affiliate_code', $user_comming_affiliate)->first();
        } else {
            return 'not have children';
        }

        return $user_master->id;
    }
}
