<?php

namespace App\Http\Controllers\Boot;

use Carbon\Carbon;
use App\Models\bot;
use App\Models\bots_usdt;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BootController extends Controller
{
    use ResponseJson;


    public function AllBot()
    {
        $botOrders = Bot::select('id', 'bot_name as botName', 'created_at')->get();

        $botOrders->each(function ($bot) {

            $bot->currency = explode('_', $bot->botName)[0] . "-USDT";


            $botOrderDates = $bot->bot_order->pluck('created_at');

            $maxCreatedAt = $botOrderDates->max(); // Maximum created_at date
            $minCreatedAt = $botOrderDates->min(); // Minimum created_at date
            if ($maxCreatedAt && $minCreatedAt) {
                // Calculate the difference in days between max and min created_at
                $totleDay = $maxCreatedAt->diffInDays($minCreatedAt);

                $bot->totalDays = $totleDay . " " . "days";
                $totalProfit = $bot->bot_order->where('side', 'sell')->sum('profit');

                $bot->profitPercentage = number_format($totalProfit, 2) . "" . "%"; // Approximate to two decimal places

            } else {
                $bot->totalDays = null; // Handle the case when there are no valid dates.
                $bot->percentage = null; // Handle the case when there's no valid data for percentage calculation.
                $bot->formattedPercentage = null; // Set formatted percentage to null in case of missing data.
            }
            unset($bot->bot_order);
        });

        return $botOrders;
    }

    public function oneBot(Request $request)
    {

        $singleBot = Bot::find($request['bot_id']);
        $currency = explode('_', $singleBot->bot_name)[0] . "-USDT"; //currency
        $singleBot->currency = $currency;

        // for totle Days
        $botDays = $singleBot->bot_order->pluck('created_at');
        $maxCreatedAt = $botDays->max(); // Maximum created_at date
        $minCreatedAt = $botDays->min();
        $totleDay = $maxCreatedAt->diffInDays($minCreatedAt);
        $singleBot->startActive = $minCreatedAt->toDateString() . " " . "-" . $totleDay . "day";
        // end for total days

        // for totle precantage per day and peer month and peer years



        $botProfit = $singleBot->bot_order->sum('profit'); // all sum profit
        $totalBuy = $singleBot->bot_order->where('side', 'buy')->sum('price');
        $chart = $singleBot->bot_order->where('side', 'sell')->pluck('profit'); //chart





        if ($totalBuy > 0) {
            $profitPercentage = ($botProfit / $totalBuy) * 100;
        } else {
            $profitPercentage = 0; // Handle the case where totalBuy is zero to avoid division by zero error.
        }


        if ($botProfit !== null) {
            // Average per day
            $averagePerDay = $botProfit / 365;
            $averagePerMonth = $botProfit / 12;
            $averagePerYears = $botProfit / 1;


            // for add in singlebot


            $singleBot->profitPercentage = number_format($botProfit, 2) . "" . "%"; // Approximate to two decimal places

            $singleBot->averagePerDay = number_format($averagePerDay, 2) . "" . "%";
            $singleBot->averagePerMonth = number_format($averagePerMonth, 2) . "" . "%";
            $singleBot->averagePerYears = number_format($averagePerYears, 2) . "" . "%";
        } else {
            $singleBot->profitPercentage = null; // Approximate to two decimal places

            $singleBot->averagePerDay = null;
            $singleBot->averagePerMonth = null;
            $singleBot->averagePerYears = null;
        }

        $singleBot->chart = $chart;

        unset($singleBot->bot_order);

        return $singleBot;
    }
}
