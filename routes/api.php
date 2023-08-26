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
use App\Http\Controllers\api\v2\AcademyController;
use App\Http\Controllers\api\v2\AdminController;
use App\Http\Controllers\api\v2\CategoryController;
use App\Http\Controllers\api\v2\CompanyController;
use App\Http\Controllers\api\v2\CurrencyController;
use App\Http\Controllers\api\v2\PurchaseController;
use App\Http\Controllers\api\v2\SkillController;
use App\Http\Controllers\api\v2\ProductController;
use App\Http\Controllers\api\v2\PlaySetController;
use App\Http\Controllers\AppController;
use App\Mail\RequestCallMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::prefix('maintanance')->group(function () {
    Route::get('down', function () {
        Artisan::call('down');
    });
    Route::get('up', function () {
        Artisan::call('up');
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
    Route::post('firebaseLogin', [AuthController::class, 'firebaseLogin']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('changePassword', [AuthController::class, 'changePassword']); // new
    Route::post('checkUserExist', [AuthController::class, 'checkUserExist']);
    Route::post('checkOtp', [AuthController::class, 'CheckOtpFunction']); // new
    Route::post('sendOtp', [AuthController::class, 'SendOtpFunction']); //new
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
        Route::post('endorse', [SkillController::class, 'endorse']);
        Route::get('endorsed', [SkillController::class, 'endorsed']);
        Route::apiResource('bookings', M_BookingController::class);
        Route::get('booking/cancel/{id}', [M_BookingController::class, 'cancelBooking']);
        // Route::post('slot', [BookingController::class, 'slot']); //work
        // Route::post('nslot', [AdminController::class, 'Nslot']);
        Route::post('addPlayers', [AppController::class, 'addPlayer']);
        Route::get('searchPlayers/{keyword}', [AppController::class, 'searchPlayer']);
        Route::post('notifyPlayers', [AppController::class, 'notifyPlayer']);
        Route::get('product', [ProductController::class, 'index']);
        Route::get('playset', [PlaysetController::class, 'index']);
        Route::get('academy', [AcademyController::class, 'index']);
        Route::post('purchasePlay', [PurchaseController::class, 'playset']);
        Route::post('purchaseProduct', [PurchaseController::class, 'product']);
        Route::post('purchaseAcademy', [PurchaseController::class, 'academy']);
    });
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:Admin', 'auth:api']], function () {
    //create courts & services & notification & company & currency & payset & category & acaedemy
    Route::apiResource('category', CategoryController::class); //
    Route::apiResource('currency', CurrencyController::class); //
    Route::apiResource('court', A_CourtController::class); //
    Route::apiResource('service', A_ServiceController::class); //
    Route::apiResource('company', CompanyController::class); //
    Route::apiResource('academy', AcademyController::class); //work
    Route::apiResource('playset', PlaysetController::class); //
    Route::apiResource('product', ProductController::class); //
    // Route::apiResource('promo', AdminController::class);
    Route::apiResource('events', A_EventController::class); //
    Route::apiResource('bookings', A_BookingController::class, array("as" => "api")); //
    Route::post('category/relation/{id}', [CategoryController::class, 'relation']);
    Route::get('bookings/calendar/{date}', [A_BookingController::class, 'index']);
    Route::post('news/create', [A_NewsController::class, 'store']); //
    Route::get('payment/all', [A_PaymentController::class, 'index']);
    Route::post('payment/charge', [A_PaymentController::class, 'store']);
    Route::post('payment/refund', [A_PaymentController::class, 'refund']);
    Route::post('user/create', [A_UserController::class, 'store']);
    Route::get('users/all', [A_UserController::class, 'index']);
});
Route::get('admin/roles/{user}', [AdminController::class, 'roles'])->middleware(['role:superAdmin', 'auth:api']);
Route::post('admin/role', [AdminController::class, 'changeRole'])->middleware(['role:superAdmin', 'auth:api']);


Route::group(['prefix' => 'academy', 'middleware' => ['role:Academy', 'auth:api']], function () {
    Route::post('session', [AdminController::class, 'session']);
    Route::post('package', [AdminController::class, 'package']);
    Route::post('trainer', [AdminController::class, 'trainer']);
    Route::post('description', [AdminController::class, 'description']);
});
