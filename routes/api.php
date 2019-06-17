<?php

use Illuminate\Http\Request;

// test password reset
Route::group(['namespace' => 'API', 'prefix' => 'password'], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});
Route::group(['namespace' => 'API', 'prefix' => 'restaurant-password'], function () {
    Route::post('/create/', 'PasswordResetController@restaurantCreateToken');
    Route::get('find/{token}', 'PasswordResetController@restaurantFindToken');
    Route::post('reset', 'PasswordResetController@restRestaurantPassword');
});
Route::group(['namespace' => 'API'], function () {
//    restaurant register && login
        Route::post('restaurant/login', 'ResturantController@login')->name('restaurant/login');
    Route::post('restaurant/register', 'ResturantController@store')->name('restaurant/register');
//    client register && login
    Route::post('client/login', 'ClientController@login')->name('client/login');
    Route::post('client/register', 'ClientController@store')->name('client/register');
//    must be auth as restaurant
    Route::group(['middleware' => 'auth:restaurant'], function () {
        Route::post('restaurant-info', 'ResturantController@show')->name('restaurant-info');
        Route::post('toggle-status', 'ResturantController@toggleStatus')->name('toggle-status');
        Route::get('restaurant-menu', 'ResturantController@menu')->name('restaurant-menu');
        Route::get('comment-rating', 'ResturantController@commentsAndRating')->name('comment-rating');
        Route::post('add-product', 'ProductController@store')->name('add-product');
        Route::post('edit-product', 'ProductController@update')->name('edit-product');
        Route::post('add-offer', 'OfferController@addOffer')->name('add-offer');
        Route::get('all-current-offers', 'OfferController@allCurrentOffers')->name('all-current-offers');
        Route::get('all-offers', 'OfferController@allOffers')->name('all-offers');
//        all  orders
//        Route::get('restaurant/orders', 'OrderController@getRestaurantOrders')->name('restaurant/orders');
        Route::get('new/orders', 'OrderController@restaurantNewOrders')->name('new/orders');
        Route::post('restaurant/accept/order', 'OrderController@restaurantAcceptOrder')->name('restaurant/accept/order');
        Route::post('/restaurant/reject/order', 'OrderController@restaurantRejectOrder')->name('/restaurant/reject/order');
        \Illuminate\Support\Facades\Route::get('current/restaurant/orders' , 'OrderController@restaurantCurrentOrders')->name('current/restaurant/orders');
        \Illuminate\Support\Facades\Route::post('restaurant/deliver/order' , 'OrderController@restaurantDeliverOrder')->name('restaurant/deliver/order');
        \Illuminate\Support\Facades\Route::get('restaurant/get/delivered/orders', 'OrderController@restaurantDeliveredOrders')->name('restaurant/get/delivered/orders');
        \Illuminate\Support\Facades\Route::get('payment' , 'PaymentController@payment')->name('payment');
    });

//    clients
    Route::group(['middleware' => 'auth:client'], function () {
        Route::post('comment/restaurant', 'CommentController@create')->name('comment/restaurant');
//        wait till creating notifications module
        Route::get('all/notifications', 'NotificationController@all')->name('all/notifications');
        Route::post('create/order', 'OrderController@create')->name('create/order');
        Route::get('in-past/orders', 'OrderController@clientInPastOrders')->name('in-past/orders');
        Route::get('current/orders', 'OrderController@clientCurrentOrders')->name('current/orders');
        Route::post('client/accept/order', 'OrderController@clientAcceptOrder')->name('client/accept/order');
        Route::post('client/reject/order', 'OrderController@clientRejectOrder')->name('client/reject/order');
//        Route::post('contact-us', 'ContactsController@contacts')->name('contacts-us');


    });
//    client without any auth
    Route::get('all-cities', 'GeneralController@allCities')->name('all-cities');
    Route::get('all-districts', 'GeneralController@allCities')->name('all-districts');
    Route::get('current-restaurants', 'ClientController@getAllRestaurant')->name('current-restaurants');
    Route::post('restaurant/info', 'ClientController@showRestaurant')->name('restaurant/info');
    Route::post('comments/ratings', 'CommentController@allComments')->name('comments/ratings');
    Route::get('new/offers', 'OfferController@newOffers')->name('new/offers');
    // move it out the gard
    Route::get('about', 'AboutController@index')->name('about');
    // Illuminate\Routing\RouteRegistrar:: what is that
    Route::get('social-media', 'AboutController@socialMedia')->name('social-media');
    Route::post('contact-us', 'ContactsController@contacts')->name('contacts-us');

});


