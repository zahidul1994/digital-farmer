<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\RoleController;
use App\Http\Controllers\Common\UserController;
use App\Http\Controllers\Common\NotificationController;
use App\Http\Controllers\Common\BrandController;
use App\Http\Controllers\Common\SupplierController;
use App\Http\Controllers\Common\PurchaseController;
use App\Http\Controllers\Common\SupplierDueController;
use App\Http\Controllers\Common\ShopCurrentStockController;
use App\Http\Controllers\Common\CustomerController;
use App\Http\Controllers\Common\VideoUploadController;

Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::post('user-status', [UserController::class, 'updateStatus'])->name('userStatus');
Route::get('admin-login-as-employee/{id}', [UserController::class, 'loginAsEmployee']);

##user 
Route::resource('setting', SettingController::class);

##notification
 Route::resource('notifications', NotificationController::class);


Route::resource('video-uploads', VideoUploadController::class);
Route::get('video-upload-status', [VideoUploadController::class, 'updateStatus'])->name('videoUploadStatus');
Route::get('video-link/{id}',  [VideoUploadController::class,'getDemoVideo']);
##for hole system  product finding
Route::get('find-product', [ProductController::class, 'findProduct'])->name('findProduct');

##purchase 
Route::resource('purchases', PurchaseController::class);
Route::post('purchase-status', [PurchaseController::class, 'updateStatus'])->name('purchaseStatus');

##brands 
Route::resource('brands', BrandController::class);
Route::post('brand-status', [BrandController::class, 'updateStatus'])->name('brandStatus');

##supplier 
Route::resource('suppliers', SupplierController::class);
Route::post('supplier-status', [SupplierController::class, 'updateStatus'])->name('supplierStatus');



##supplier due
Route::resource('supplier-due', SupplierDueController::class);
Route::get('supplier-due-pdf/{id}', [SupplierDueController::class, 'supplierDuePdf'])->name('supplierDuePdf');


##shop current stock
Route::resource('shop-current-stocks', ShopCurrentStockController::class);

##customer 
Route::resource('customers', CustomerController::class);
Route::post('customer-status', [CustomerController::class, 'updateStatus'])->name('customerStatus');


##sale 
Route::resource('sales', SaleController::class);
Route::post('find-customer', [SaleController::class, 'findCustomer'])->name('findCustomer');
Route::post('find-shop-current-stock', [SaleController::class, 'findShopCurrentStock'])->name('findShopCurrentStock');