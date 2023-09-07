<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\SliderController;
use App\Http\Controllers\SuperAdmin\PageController;
use App\Http\Controllers\SuperAdmin\ShopController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\CommandController;
use App\Http\Controllers\SuperAdmin\PackageController;
use App\Http\Controllers\SuperAdmin\PaymentController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\CategoryController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\DatabackupController;
use App\Http\Controllers\SuperAdmin\WalletController;
use App\Http\Controllers\SuperAdmin\ProfileController;
use App\Http\Controllers\SuperAdmin\ContactController;
use App\Http\Controllers\Superadmin\DivisionController;
use App\Http\Controllers\SuperAdmin\DistrictController;
use App\Http\Controllers\SuperAdmin\ThanaController;
use App\Http\Controllers\SuperAdmin\SubCategoryController;

Route::group(['as'=>'superadmin.','prefix' =>'superadmin', 'middleware' => ['auth', 'superadmin']], function(){
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //common
  include __DIR__ . '/common.php';



  ##slider
Route::resource('sliders', SliderController::class);
Route::post('slider-status', [SliderController::class, 'updateStatus'])->name('sliderStatus');


  //admin
  Route::resource('admins', AdminController::class);
Route::post('admin-status', [AdminController::class, 'updateStatus'])->name('adminStatus');
Route::get('superadmin-login-as-admin/{id}', [AdminController::class, 'loginAsAdmin']);
  
//admin
  Route::resource('packages', PackageController::class);
  Route::post('package-status', [PackageController::class, 'updateStatus'])->name('packageStatus');

  
  
##caregory 
Route::resource('categories', CategoryController::class);
Route::post('category-status', [CategoryController::class, 'updateStatus'])->name('categoryStatus');

##subcategory 
Route::resource('sub-categories', SubCategoryController::class);
Route::post('sub-category-status', [SubCategoryController::class, 'updateStatus'])->name('subcategoryStatus');

##page 
Route::resource('pages', PageController::class);
Route::post('page-status', [PageController::class, 'updateStatus'])->name('pageStatus');

##shop 
Route::resource('shops', ShopController::class);
Route::post('shop-status', [ShopController::class, 'updateStatus'])->name('shopStatus');

##databasebackup
 Route::resource('databases', DatabackupController::class);


 ##settings 
Route::resource('payments', PaymentController::class);
Route::post('payment-status', [PaymentController::class, 'updateStatus'])->name('paymentStatus');

##profile 
Route::get('profiles', [ProfileController::class, 'index'])->name('profiles');
Route::post('profile-update', [ProfileController::class, 'profilesUpdate'])->name('profilesUpdate');
Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('passwordUpdate');


Route::get('setting', [SettingController::class, 'index'])->name('setting');
Route::post('setting', [SettingController::class, 'settingUpdate'])->name('settingUpdate');
## smtp settings
Route::get('smtp-settings', [SettingController::class, 'smtpIndex'])->name('smtpIndex');
Route::post('smtp-settings',[SettingController::class,'envKeyUpdate'])->name('envKeyUpdate');

##artisan command  start
Route::get('artisan-command',[CommandController::class,'index'])->name('artisanCommand');
Route::get('artisan/{command}/{param}',[CommandController::class,'artisan']);


##wallet   start
Route::resource('wallets', WalletController::class);
Route::post('wallet-status', [WalletController::class, 'updateStatus'])->name('walletStatus');


##contacts   start
Route::resource('contacts', ContactController::class);
 Route::post('deleteallemail', [ContactController::class, 'allEmailDestroy'])->name('allEmailDestroy');

##location   start
Route::resource('divisions', DivisionController::class);
Route::resource('districts', DistrictController::class);
Route::resource('thanas', ThanaController::class);



});


