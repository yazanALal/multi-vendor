<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CartController;
use App\Http\Controllers\api\FollowerController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\OrderController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\ReviewController;
use App\Http\Controllers\api\StoreController;
use App\Http\Controllers\api\WishListController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'logIn']);
    Route::post('signup', [AuthController::class, 'register']);
    Route::post('admin', [AdminAuthController::class, 'login']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth_user');
    Route::get('admin/logout', [AdminAuthController::class, 'logout'])->middleware('admin');
});

Route::middleware(['auth_user', 'no-store'])->prefix('user')->group(function () {
    Route::post('create-store', [StoreController::class, 'createStore']);
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::post('create-category', [CategoryController::class, 'store']);
});


Route::middleware('auth_user')->group(function () {
    Route::post('search', [ProductController::class, 'searchProduct']);
    Route::get("/stores",[StoreController::class,'index']);
    Route::get("/store/show",[StoreController::class,'show']);
    Route::get("/store/follow",[FollowerController::class,"follow"]);
    Route::get("/follow",[FollowerController::class,"followingStores"]);
    Route::get("/store/un-follow",[FollowerController::class,"unFollow"]);
    Route::post("/wishlist/add",[WishListController::class,"addToWishList"]);
    Route::get("/wishlist/remove",[WishListController::class,"delete"]);
    Route::get("/wishlist/index/",[WishListController::class,"index"]);
    Route::get("/cart/index/",[CartController::class,"index"]);
    Route::post("/cart/add/", [CartController::class, "addToCart"]);
    Route::post("/cart/update/", [CartController::class, "update"]);
    Route::get("/cart/delete/", [CartController::class, "delete"]);
    Route::post("/checkout", [OrderController::class, "checkout"]);
    Route::post("/add/review", [ReviewController::class, "store"]);
    Route::get('product/review',[ReviewController::class,'index']);
    Route::get('history',[OrderController::class,'orderHistory']);
});



Route::middleware(['auth_user', 'store'])->prefix('store')->group(function () {
    Route::get('my-store', [StoreController::class, 'myStore']);
    Route::post('update-store', [StoreController::class, 'editStore']);
    Route::get('remove-store', [StoreController::class, 'deleteStore']);
    Route::post('image', [StoreController::class, 'storeImage']);
    Route::post('create-product', [ProductController::class, 'storeProduct']);
    Route::post('edit-product', [ProductController::class, 'updateProduct']);
    Route::post('delete-product', [ProductController::class, 'softDelete']);
    Route::get('trash', [ProductController::class, 'trash']);
    Route::post('restore-product', [ProductController::class, 'restore']);
    Route::get('empty-trash', [ProductController::class, 'emptyTrash']);
    Route::post('force-delete', [ProductController::class, 'forceDelete']);
    Route::get('orders', [OrderController::class, 'showOrders']);
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::post('status/change',[OrderController::class, 'changeOrderStatus']);
});



