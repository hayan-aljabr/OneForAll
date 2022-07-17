<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Models\Order;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

/*Route::group(['middleware' => ['auth:sanctum']], function () {

});*/
Route::get('/home',[ProductController::class,"index"] );
Route::middleware('auth:api')->put('users/{id}', [UserController::class,"update"]);
Route::post('/store', [UserController::class, "create"]);
Route::get('/category', [CategoryController::class, "index"]);
Route::post('/login', [UserController::class, "login"]);
Route::middleware('auth:api')->delete('users/{user}', [UserController::class, "destroy"]);
//Route::put('updateProdcut/{producr}',);
Route::middleware('auth:api')->Resource('/products', ProductController::class)->except(['index','show']);
Route::get('/products',[ProductController::class, 'index']);
Route::get('/products/{product_name}',[ProductController::class, 'show']);


Route::middleware('auth:api','access.controll')->resource('/categories', CategoryController::class);
//Route::middleware('auth:api')->post('/storeCate', [CategoryController::class, "store"]);
//Route::middleware('auth:api')->post('/storePro', [ProductController::class, "store"]);
Route::middleware('auth:api')->post('/logout', [UserController::class, "logout"]);
Route::get('/productsCat/{id}' , [ProductController::class, "showByCategory"]);
Route::middleware('auth:api')->Resource('cart', CartController::class)->except(['update', 'show','destroy']);
//add products to the cart
Route::middleware('auth:api')->post('/cart/products/{product_id}', [ App\Http\Controllers\CartController::class, 'addProducts']);
Route::middleware('auth:api')->get('/getCartItems/{id}',[ App\Http\Controllers\CartController::class, 'getCartItems']);
 //create an order from the cart
Route::middleware('auth:api')->post('/order', [ App\Http\Controllers\CartController::class, 'Order']);

//retrieve a list of products in the order
Route::middleware('auth:api')->get('/order/{order_name}', [ App\Http\Controllers\OrderController::class, 'show']);

Route::middleware('auth:api','access.controll')->delete('admin/users/{user}',[AdminController::class, "destroy"]);


/*Route::prefix('admin')->group(function(){
    Route::post('login', AuthController::class , 'login');
    Route::post('signup', AuthController::class , 'signUp');

});*/
//Route::post('/logout', [UserController::class, "logout"]);




