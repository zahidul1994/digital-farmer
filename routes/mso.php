<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mso\WalletController;
use App\Http\Controllers\Mso\PaymentController;
use App\Http\Controllers\Mso\DashboardController;
use App\Http\Controllers\Mso\ProfileController;

Route::group(['as'=>'mso.','prefix' =>'mso', 'middleware' => ['auth','mso']], function(){
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

##profile 
Route::get('profiles', [ProfileController::class, 'index'])->name('profiles');
Route::post('profile-update', [ProfileController::class, 'profilesUpdate'])->name('profilesUpdate');
Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');
Route::post('software-rating', [ProfileController::class, 'softwareRating'])->name('softwareRating');
Route::get('login-admin/{id}', [DashboardController::class, 'loginAdmin'])->name('loginAdmin');
   
//common
  include __DIR__ . '/common.php';

  ## wallet
Route::resource('wallets', WalletController::class);
## payment
Route::get('payments',[PaymentController::class, 'index'])->name('payments');
Route::post('payments-create', [PaymentController::class, 'create'])->name('paymentsCreate');
Route::post('payments', [PaymentController::class, 'store'])->name('paymentsStore');
Route::get('invoice-download/{id}', [WalletController::class, 'downloadInvoice'])->name('downloadInvoice');
});

