<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FeaturedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Models\Order;
use App\Models\Role;
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
Route::get('users', [UserController::class,"index"]);//->>>>>>>>>>bilal
Route::get('users/{id}', [UserController::class,"show"]);//->>>>>>>>>>>>>>bilal
Route::post('/store', [UserController::class, "create"]);
Route::get('/category', [CategoryController::class, "index"]);
Route::post('/login', [UserController::class, "login"]);
Route::middleware('auth:api')->delete('users/{user}', [UserController::class, "destroy"]);
//Route::put('updateProdcut/{producr}',);
Route::middleware('auth:api')->Resource('/products', ProductController::class)->except(['index','show']);
Route::post('/report',[ReportController::class, 'store']);
Route::get('/report',[ReportController::class, 'index']);

//Route::post('products/{product_id}/reviews',[ReviewController::class, 'store']);
Route::middleware('auth:api')->apiResource('products/{product}/reviews',ReviewController::class)->except(['show']);
//Route::group(['prefix'=>'products'],function(){
  //  Route::Resource('/{product}/reviews',[ReviewController::class]);

//});
Route::middleware('auth:api')->get('/myProducts',[ProductController::class,'showByUser']);
Route::get('/products/{name}',[ProductController::class, 'show']);
Route::get('/productPhoto/{id}', [ProductController::class,'getProductImage']);

Route::get('/products',[ProductController::class, 'index']);
Route::get('/productsfull/{product}',[ProductController::class, 'productinfo']);

Route::apiResource('/wishlist',WishlistController::class)->except(['create','show','edit','update']);


Route::get('/products/{product_name}',[ProductController::class, 'show']);
Route::get('/product',[ProductController::class, 'filter']);

Route::get('/homepage',[ProductController::class, 'homepageindex']);
Route::get('/getProduct/{id}',[ProductController::class, 'getProduct']);


Route::middleware('auth:api','access.controll')->resource('/categories', CategoryController::class);
//Route::middleware('auth:api')->post('/storeCate', [CategoryController::class, "store"]);
//Route::middleware('auth:api')->post('/storePro', [ProductController::class, "store"]);
Route::middleware('auth:api')->post('/logout', [UserController::class, "logout"]);
Route::get('/productsCat' , [ProductController::class, "showByCategory"]);
Route::middleware('auth:api')->Resource('cart', CartController::class)->except(['update', 'show','destroy']);
//add products to the cart
Route::middleware('auth:api')->post('/cart/products/{product_id}', [ App\Http\Controllers\CartController::class, 'addProducts']);
Route::middleware('auth:api')->get('/getCartItems/{id}',[ App\Http\Controllers\CartController::class, 'getCartItems']);
 //create an order from the cart
Route::middleware('auth:api')->post('/order', [ App\Http\Controllers\CartController::class, 'Order']);
Route::middleware('auth:api')->post('/profile/change-password', [ App\Http\Controllers\ProfileController::class, 'change_password']);
Route::middleware('auth:api')->post('/profile/update-profile', [ App\Http\Controllers\ProfileController::class, 'update_profile']);

Route::delete('/deletefromcart/{id}',[CartController::class, 'deletefromcart']);

//retrieve a list of products in the order
Route::middleware('auth:api')->get('/order/{order_id}', [ App\Http\Controllers\OrderController::class, 'show']);

Route::get('/featured',[FeaturedController::class, 'index']);
Route::post('/featured',[FeaturedController::class, 'store']);
Route::delete('/featured/{f}',[FeaturedController::class, 'destroy']);

Route::middleware('auth:api','access.controll')->delete('admin/users/{user}',[AdminController::class, "destroy"]);
Route::middleware('auth:api','access.controll')->delete('admin/products/{product}',[AdminController::class, "deleteProduct"]);
Route::get('admin/productsAll',[AdminController::class, "adminSeeProducts"]);
Route::get('admin/usersAll',[AdminController::class, "adminSeeUsers"]);
Route::get('admin/lastuser',[AdminController::class, "adminLastUsers"]);
Route::get('admin/lastproduct',[AdminController::class, "adminLastProducts"]);
Route::get('/test',function(){
  return "dd";
});


/*Route::prefix('admin')->group(function(){
    Route::post('login', AuthController::class , 'login');
    Route::post('signup', AuthController::class , 'signUp');

});*/
//Route::post('/logout', [UserController::class, "logout"]);




