<?php

use App\Models\plan;
use App\Models\Massage;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
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

// for Admin
Route::middleware(['SuperWithAdmin'])->prefix('Admin')->group(function () {
    Route::apiResource('Recommendation', RecommendationController::class);
    Route::get('adminPlan', [RecommendationController::class,'adminPlan']); //for admin in dashbord
    Route::post('chatAdmin',[chatAdviceAdminController::class,'chat']);
    Route::post('adviceAdmin',[chatAdviceAdminController::class,'Advice']);
    Route::post('adminChatPlan',[chatAdviceAdminController::class,'StoreMassageAdmin']); // FOR SEND MASSAGE ADMIN

    Route::post('adminForPlan',[chatAdviceAdminController::class,'adminForPlan']);
    Route::apiResource('post', PostController::class);
    // for chat group Delete MessageS
    Route::post('messagePlan', [ChatActions::class, 'deletePlan']);
    Route::delete('messageSuper/{id}', [ChatActions::class, 'deleteMessageSuper']);
    Route::post('banPlan', [ChatActions::class, 'banPlan']);
    Route::post('unbanPlan', [ChatActions::class, 'unbanPlan']);

    // Get Bot Controller
    Route::get('/bot-controller', [FrontController::class, 'getBotData']);
    Route::post('/set-bot-controller', [FrontController::class, 'setBotData']);

});

Route::prefix('Admin')->middleware('SuperAdmin')->group(function () {
    Route::get('loges',[getLogesController::class,'index']); //for binance
    Route::delete('loges/{id}',[getLogesController::class,'deleteloges']);

    Route::resource('video', videoController::class);
    Route::resource('posts', PostController::class);
    Route::apiResource('plan', PlanController::class);
    Route::resource('archive', ArchiveController::class);
    // Route::apiResource('Recommendation', RecommendationController::class);
    // for User in admin
    Route::apiResource('User', All_UserController::class);
    Route::get('get_user/{id}', [All_UserController::class, 'get_user'])->name('get_user');
    Route::get('search/{id}', [All_UserController::class, 'serach'])->name('serach');
    Route::get('selectUserFromPlan/{id}', [All_UserController::class, 'selectUserFromPlan'])->name('selectUserFromPlan');

    Route::get('get_all_subscrib/{id}', [All_UserController::class, 'get_all_subscrib']);
    Route::apiResource('banned',bannedController::class);

    Route::resource('telegram', TelegramController::class);
    // Chat Advice
    Route::get('ChatAdvice', [ChatAdviceController::class, 'getChat']);
    Route::post('ChatAdvice_store', [ChatAdviceController::class, 'store']);

    Route::apiResource('coupons', CouponController::class);
    Route::apiResource('payment', PaymentController::class);
    // Route::apiResource('post', PostController::class);
    // Withdraw
    Route::apiResource('withdraw', TransferManyController::class);
    // for pending
    Route::get('pending', [PayController::class, 'pending']);
    Route::post('ActivePending', [PayController::class, 'ActivePending']);
    // dataUser AdminPanel
    Route::get('dataUserCount', [UserDataAdminPanel::class, 'UserCount']);
    Route::get('dataUserCountBanned', [UserDataAdminPanel::class, 'UserCountBanned']);
    Route::get('dataAdminCount', [UserDataAdminPanel::class, 'AdminCount']);
    Route::get('dataAdvicesCount', [UserDataAdminPanel::class, 'AdvicesCount']);
    Route::get('dataLastPaymentCount', [UserDataAdminPanel::class, 'LastPaymentCount']);
    Route::get('dataLastAdviceCount', [UserDataAdminPanel::class, 'LastAdviceCount']);
    // for sofdelete
    Route::get('softDeleteUser', [AuthController::class, 'softDeleteUser']);

    // Admin Notification
    Route::apiResource('NotificationPlans',NotificationPlansController::class);
Route::put('restoreSoftDeleteUser/{id}', [AuthController::class, 'restoreSoftDeleteUser']);

// Bot

Route::apiResource('bot-transfer', BotTransferController::class);
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
Route::post('crybto', [TabsController::class, 'crybto']);



Route::get('get-recmo-data/{recmoId}', [TabsController::class, 'getRecmoData'])->name('get-recmo-data');
