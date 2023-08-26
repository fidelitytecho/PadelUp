<?php

use App\Http\Controllers\api\v1\admin\A_BookingController;
use App\Http\Controllers\api\v1\admin\A_CourtController;
use App\Http\Controllers\api\v1\admin\A_EventController;
use App\Http\Controllers\api\v1\admin\A_NewsController;
use App\Http\Controllers\api\v1\admin\A_PaymentController;
use App\Http\Controllers\api\v1\admin\A_ServiceController;
use App\Http\Controllers\api\v1\admin\A_UserController;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\mobile\M_BookingController;
use App\Http\Controllers\api\v1\mobile\M_CategoryController;
use App\Http\Controllers\api\v1\mobile\M_NewsController;
use App\Http\Controllers\api\v1\PaymobController;
use App\Mail\RequestCallMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

Route::group(['prefix' => 'artisan'], function () {
    Route::get('/', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('view:cache');
        Artisan::call('route:cache');
        Artisan::call('optimize');
        dd("Cache is cleared");
    });
});

Route::group(['prefix' => 'cronjob'], function () {
    Route::get('restartQueue', function (){
        Artisan::call('queue:restart');
        sleep(3);
        Artisan::call('queue:work');
    });
});

Route::group(['prefix' => 'mail'], function () {
    Route::get('requestCall/{mobile}', function (Request $request) {
        Mail::to('omar_elolimi@hotmail.com')->send(new RequestCallMail($request->mobile));
    });
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('loginWithMobile', [AuthController::class, 'loginWithMobile']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('changePassword/{userID}', [AuthController::class, 'changePassword']);
    Route::post('checkUserExist', [AuthController::class, 'checkUserExist']);
    Route::post('checkOtp', [AuthController::class, 'CheckOtpFunction']);
    Route::post('sendOtp', [AuthController::class, 'SendOtpFunction']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('updateProfile', [AuthController::class, 'updateProfile']);
});

Route::group(['prefix' => 'paymob'], function () {
    Route::post('status', PaymobController::class);
});

Route::group(['prefix' => 'app'], function () {
    Route::get('news', M_NewsController::class);
    Route::get('category', [M_CategoryController::class, 'index']);
    Route::get('category/available/{date}/{duration}', [M_CategoryController::class, 'availableTimes']);
    Route::get('category/next/available/{date}/{duration}', [M_CategoryController::class, 'nextAvailableDay']);
    Route::group(['middleware' => ['role:Customer', 'auth:api']], function () {
        Route::apiResource('bookings', M_BookingController::class);
        Route::get('booking/cancel/{id}', [M_BookingController::class, 'cancelBooking']);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:super_admin', 'auth:api']], function () {
    Route::post('news/create', [A_NewsController::class, 'store']);
    Route::apiResource('events', A_EventController::class);
    Route::get('bookings/calendar/{date}', [A_BookingController::class, 'index']);
    Route::apiResource('bookings', A_BookingController::class, array("as" => "api"));
    Route::get('courts', [A_CourtController::class, 'index']);
    Route::get('services', [A_ServiceController::class, 'index']);
    Route::get('payment/all', [A_PaymentController::class, 'index']);
    Route::post('payment/charge', [A_PaymentController::class, 'store']);
    Route::post('payment/refund', [A_PaymentController::class, 'refund']);
    Route::post('user/create', [A_UserController::class, 'store']);
    Route::get('users/all', [A_UserController::class, 'index']);
});
