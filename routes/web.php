<?php

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use UniSharp\LaravelFilemanager\Lfm;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OnChangeController;
use App\Http\Controllers\Common\VideoUploadController;

// Route::fallback(function () {
//     abort(404);
// });

Route::get('/', [HomeController::class,'index']);


Auth::routes();
Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
    Lfm::   routes();
});


Route::get('/home', [HomeController::class,'index'])->name('home');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('register', [HomeController::class,'index']);
Route::post('login', [LoginController::class,'login'])->name('login');


##get agent value 

##get proudct value 
Route::get('get-district-info/{id}', [OnChangeController::class,'getDistrict'])->name('getDistrict');
Route::get('get-thana-info/{id}', [OnChangeController::class,'getThana'])->name('getThana');
Route::post('get-area-info', [OnChangeController::class,'getArea'])->name('getArea');



// SSLCOMMERZ Start
// buy package
Route::get('/ssl/pay', 'PublicSslCommerzPaymentController@index')->name('ssl.pay');
Route::POST('/success', 'PublicSslCommerzPaymentController@success');
Route::POST('/fail', 'PublicSslCommerzPaymentController@fail');
Route::POST('/cancel', 'PublicSslCommerzPaymentController@cancel');
Route::POST('/ipn', 'PublicSslCommerzPaymentController@ipn');
Route::POST('/ecommercesuccess', 'OrderSslCommerzPaymentController@ecommercesuccess');
Route::POST('/ecommercefail', 'OrderSslCommerzPaymentController@ecommercefail');
Route::POST('/ecommercecancel', 'OrderSslCommerzPaymentController@ecommercecancel');

//SSLCOMMERZ END

Route::get('/ssl/redirect/{trans_id}','PublicSslCommerzPaymentController@status');

// buy package
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');
//Stripe END

// bkash
Route::get('/pay', [BkashController::class, 'pay'])->name('pay');
Route::post('/bkash/create', [BkashController::class, 'create'])->name('create');
Route::post('/bkash/execute', [BkashController::class, 'execute'])->name('execute');
Route::get('/bkash/success', [BkashController::class, 'success'])->name('success');
Route::get('/bkash/fail', [BkashController::class, 'fail'])->name('fail');

//contact

Route::post('/contact',  [HomeController::class,'contactStore'])->name('contactStore');
Route::get('/demo-video',  [VideoUploadController::class,'getDemoVideo'])->name('getDemoVideo');