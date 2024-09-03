<?php

use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AdminRatingController;
use App\Http\Controllers\user\UserRatingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\user\UserBorrowRecordController;
use App\Http\Controllers\Admin\AdminBorrowRecordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');  //
    Route::post('register', 'register'); //
});


Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout',[AuthController::class ,'logout']); //

    //only for admin
    Route::apiResource('category',CategoryController::class); 
    Route::apiResource('book',BookController::class); 
    Route::apiResource('borrow_admin',AdminBorrowRecordController::class);
    Route::put('borrowUpdatStatus/{borrow_id}',[AdminBorrowRecordController::class,'editStatus']);
    Route::apiResource('rating_admin', AdminRatingController::class)->only(['index', 'destroy']);
    Route::apiResource('user',UserController::class); 
    
    
    //only for user
    Route::apiResource('borrow_user',UserBorrowRecordController::class);
    Route::apiResource('rating_user', UserRatingController::class)->except(['store']);
    Route::post('rating_user/{book}',[UserRatingController::class,'store']);
    
});


//يمكن للمستخدم تصفح الكتب دون الحاجة إلى تسجيل الدخول
Route::get('all_books',[BookController::class , 'all_books']); 
Route::get('view_book/{book_id}',[BookController::class , 'view_book']); 

//يمكن للمستخدم تصفح التصنيفات دون الحاجة إلى تسجيل الدخول
Route::get('all_categories',[CategoryController::class , 'all_categories']); 
Route::get('view_category/{category_id}',[CategoryController::class , 'view_category']); 



