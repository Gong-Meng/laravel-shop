<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', 'PagesController@root')->name('root');

// 邮箱验证
//Route::get('/', 'PagesController@root')->name('root')->middleware('verified');

//Auth::routes(['verify' => true]);
Auth::routes();

// auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
//Route::group(['middleware' => ['auth', 'verified']], function() {
Route::group(['middleware' => ['auth']], function() {
    Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
    Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressesController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');

    Route::post('orders', 'OrdersController@store')->name('orders.store');
    Route::get('orders', 'OrdersController@index')->name('orders.index');

    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');
    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');

    // 支付宝页面回调
    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');
    // 微信支付服务端回调
    Route::get('payment/{order}/wechat', 'PaymentController@payByWechat')->name('payment.wechat');

    Route::post('orders/{order}/received', 'OrdersController@received')->name('orders.received');
    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');
    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
    Route::post('orders/{order}/apply_refund', 'OrdersController@applyRefund')->name('orders.apply_refund');

    Route::get('coupon_codes/{code}', 'CouponCodesController@show')->name('coupon_codes.show');

    Route::post('crowdfunding_orders', 'OrdersController@crowdfunding')->name('crowdfunding_orders.store');

    //分期支付
    Route::post('payment/{order}/installment', 'PaymentController@payByInstallment')->name('payment.installment');
    Route::get('installments', 'InstallmentsController@index')->name('installments.index');
    Route::get('installments/{installment}', 'InstallmentsController@show')->name('installments.show');
});

Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

// 支付宝服务端回调
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
// 微信支付服务端回调
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');

// 微信退款回调处理
Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');
