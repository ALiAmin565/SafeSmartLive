<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\videoController;
use App\Http\Controllers\bannedController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\All_UserController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\UserDataAdminPanel;
use App\Http\Controllers\ChatAdviceController;
use App\Http\Controllers\BotTransferController;
use App\Http\Controllers\TransferManyController;
use App\Http\Controllers\Binance\getLogesController;
use App\Http\Controllers\NotificationPlansController;




// routes for super with admin
Route::middleware(['SuperWithAdmin'])->group(function () {
    Route::apiResource('Recommendation', RecommendationController::class);
    Route::get('adminPlan', [RecommendationController::class, 'adminPlan']); //for admin in dashbord
    Route::post('chatAdmin', [chatAdviceAdminController::class, 'chat']);
    Route::post('adviceAdmin', [chatAdviceAdminController::class, 'Advice']);
    Route::post('adminChatPlan', [chatAdviceAdminController::class, 'StoreMassageAdmin']); // FOR SEND MASSAGE ADMIN

    Route::post('adminForPlan', [chatAdviceAdminController::class, 'adminForPlan']);
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
// routes for super  admin

Route::middleware('SuperAdmin')->group(function () {
    Route::get('loges', [getLogesController::class, 'index']); //for binance
    Route::delete('loges/{id}', [getLogesController::class, 'deleteloges']);

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
    Route::apiResource('banned', bannedController::class);

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
    Route::apiResource('NotificationPlans', NotificationPlansController::class);
    Route::put('restoreSoftDeleteUser/{id}', [AuthController::class, 'restoreSoftDeleteUser']);
    // Bot
    Route::apiResource('bot-transfer', BotTransferController::class);
});