<?php

use App\Models\plan;
use App\Models\User;
use App\Models\Massage;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ChatActions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Front\adminPlan;
use App\Http\Controllers\videoController;
use App\Http\Controllers\bannedController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PayMopController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\All_UserController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\UserDataAdminPanel;
use App\Http\Controllers\AfilliateCalculation;
use App\Http\Controllers\ChatAdviceController;
use App\Http\Controllers\Front\TabsController;
use App\Http\Controllers\Binance\buyController;
use App\Http\Controllers\BotTransferController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\TransferManyController;
use App\Http\Controllers\RecommendationController;

use App\Http\Controllers\chatAdviceAdminController;
use App\Http\Controllers\Front\ChatGroupController;
use App\Http\Controllers\Binance\getLogesController;
use App\Http\Controllers\NotificationPlansController;
use App\Http\Controllers\Front\HistoryWalteController;
use App\Http\Controllers\Binance\transactionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group([

    'middleware' => 'api',



], function ($router) {
    Route::post('create', [AuthController::class, 'create']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('checkOtp', [AuthController::class, 'checkOtp']);
    Route::post('sendOtp', [AuthController::class, 'sendOtp']);
    Route::post('resendOtp', [AuthController::class, 'reSendOtp']);
    Route::post('resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('dymnamikeLink', [AuthController::class, 'dymnamikeLink']);
    Route::post('verfiy', [AuthController::class, 'verfiy']);
    Route::post('fcmToken', [AuthController::class, 'fcmToken']);
    Route::post('affiliateUser', [AuthController::class, 'affiliate_user']);
});


Route::post('returnFree', [PayController::class, 'returnFree']);
Route::post('viewsRecmo', [RecommendationController::class, 'viewsRecmo']);

//  for Front
Route::get('videos', [TabsController::class, 'videos']);
Route::get('archive', [TabsController::class, 'Archive']);
Route::post('advice', [TabsController::class, 'Advice']);
Route::post('goo', [TabsController::class, 'goo']);
Route::get('getPosts', [TabsController::class, 'getPosts']);
Route::post('adminPlan', [adminPlan::class, 'adminPlan']);
Route::get('userExpire', [TabsController::class, 'userExpire']);
Route::post('/add-value-binance', [TabsController::class, 'addValueToBinance']);
Route::post('/submit-image-binance', [TabsController::class, 'binanceTransaction']);
Route::post('/users-binance', [TabsController::class, 'binanceTransactionUsers']);
Route::put('accept-image-binance/{ImageSubmissionBinanceId}', [TabsController::class, 'acceptImageBinance']);
Route::put('cancel-image-binance/{ImageSubmissionBinanceId}', [TabsController::class, 'cancelImageBinance']);



// form
Route::post('massage', [ChatGroupController::class, 'Massage']);
Route::post('sendmassage', [ChatGroupController::class, 'StoreMassage']);
Route::post('sendmassagesss', [ChatGroupController::class, 'StoreMassagesss']);
Route::post('withDrawMoney', [TabsController::class, 'TransfarManyClient']);
Route::post('withDrawHistroy', [TabsController::class, 'historyTransFarMany']);
Route::get('plans', [FrontController::class, 'getPlan']);
Route::post('orderpay', [FrontController::class, 'Orderpay']);
Route::post('histroyPay', [FrontController::class, 'HistroyPay']);
Route::post('paymentimage', [FrontController::class, 'UploadImagePayment']);
Route::post('SelectPlan', [FrontController::class, 'SelectPlan']);
Route::post('Recommindation', [FrontController::class, 'Recommindation']);
Route::get('testcalc/{id}', [AfilliateCalculation::class, 'afterPay']);
// deleteUser
Route::post('delete', [AuthController::class, 'deleteUser']);
//  for delete massage chat
Route::post('messageUser/{id}', [ChatActions::class, 'deleteMessageUser']);
// custom Ban User for Plan
Route::post('banPlan/{nameChannel}', [ChatActions::class, 'banPlan']);
Route::post('unbanPlan/{nameChannel}', [ChatActions::class, 'unbanPlan']);
Route::get('current_datetime', [TabsController::class, 'getCurrentDateTime']);



Route::get('myAdvice', [TabsController::class, 'myAdvice']);




Route::get('testbot', [TabsController::class, 'testbot']);





Route::post('all', [HistoryWalteController::class, 'all']);




Route::get('testbot', [TabsController::class, 'testbot']);
Route::post('testAdvice', [RecommendationController::class, 'storeApiRequest']);



Route::get('plans', [SubscripPlan::class, 'getPlan']);
Route::post('orderpay', [SubscripPlan::class, 'Orderpay']);
Route::post('histroyPay', [SubscripPlan::class, 'HistroyPay']);
Route::post('paymentimage', [SubscripPlan::class, 'UploadImagePayment']);
Route::post('SelectPlan', [SubscripPlan::class, 'SelectPlan']);
Route::post('Recommindation', [SubscripPlan::class, 'Recommindation']);

Route::get('shutdown',function(){
       $data = [
            'shutdown'=> 0,
            "userid" => 1,
         
        ];
         $response= Http::post('http://51.161.128.30:5015/shutdown', $data);
        return $responseBody = $response->body();

});

Route::post('fees_bot',[TabsController::class, 'fees_bot']);


Route::post('all', [HistoryWalteController::class, 'all']);

Route::get('test',function() {
    return User::get();
});

