<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MsoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\ProfileController;
Route::group([
  'as' => 'admin.',
  'prefix' => 'admin',
  'middleware' => ['auth', 'admin']
], function () {
  Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

##profile 
Route::get('profiles', [ProfileController::class, 'index'])->name('profiles');
Route::post('profile-update', [ProfileController::class, 'profilesUpdate'])->name('profilesUpdate');
Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');
Route::post('software-rating', [ProfileController::class, 'softwareRating'])->name('softwareRating');


##mso 
Route::resource('mso', MsoController::class);
Route::post('mso-status', [MsoController::class, 'updateStatus'])->name('msoStatus');
Route::get('admin-login-as-mso/{id}', [MsoController::class, 'loginMso'])->name('loginMso');

## wallet
Route::resource('wallets', WalletController::class);
Route::post('wallet-status', [WalletController::class, 'updateStatus'])->name('walletStatus');
## payment
Route::get('payments',[PaymentController::class, 'index'])->name('payments');
Route::post('payments-create', [PaymentController::class, 'create'])->name('paymentsCreate');
Route::post('payments', [PaymentController::class, 'store'])->name('paymentsStore');
Route::get('invoice-download/{id}', [WalletController::class, 'downloadInvoice'])->name('downloadInvoice');
##common
  include __DIR__ . '/common.php';
});
