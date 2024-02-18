<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/category/{slug}', 'HomeController@category')->name('category');
Route::get('/category-product/{slug}', 'HomeController@categoryProduct')->name('category.product');
Route::get('/product-details/{slug}', 'HomeController@productDetails')->name('product.details');
Route::get('/get/offers', 'HomeController@getOffers')->name('get.offers');
Route::post('/claim/offers', 'HomeController@claimOffers')->name('offer.claim');
Route::get('/prise-wise-filter', 'HomeController@priceWiseFilter')->name('price.wise.filter');

//pages controller
Route::get('/about-us', 'PagesController@aboutUs')->name('about.us');
Route::get('/contact-us', 'PagesController@contactUs')->name('contact');
Route::get('/how-to-buy', 'PagesController@howToBuy')->name('how.to.buy');
Route::get('/product-catalogue', 'PagesController@catalogue')->name('product.catalogue');
Route::get('/terms-and-condition', 'PagesController@termsOfCondition')->name('terms.condition');
Route::get('/delivery-policy', 'PagesController@deliveryPolicy')->name('delivery.policy');
Route::get('/return-refund-policy', 'PagesController@refundPolicy')->name('refund.policy');

//New arrivals and Current offer route
Route::get('/product/new-arrivals', 'HomeController@newArrivals')->name('new.arrivals');
Route::get('/product/current-offer', 'HomeController@currentOffer')->name('current.offer');

//cart controller
Route::get('/cart', 'CartController@index')->name('cart');
Route::post('/cart/store', 'CartController@cartStore')->name('cart.store');
Route::post('buy/now/cart/store', 'CartController@cartStoreBuyNow')->name('buy.now.cart.store');
Route::post('/cart/store/with-offer', 'CartController@cartStoreWithOffer')->name('cart.store.with.offer');
Route::post('/cart/update', 'CartController@cartUpdate')->name('cart.update');
Route::get('/cart/clear', 'CartController@cartClear')->name('cart.clear');
Route::delete('/cart/destroy/{id}', 'CartController@cartDestroy')->name('cart.destroy');

//customer wishlist route

Route::get('/customer/wishlist', 'WishListController@wishlist')->name('customer.wishlist');
Route::post('/customer/wishlist/store', 'WishListController@wishlistStore')->name('customer.wishlist.store');
Route::post('/customer/wishlist-to-cart', 'WishListController@wishlistToCart')->name('wishlist.to.cart');
Route::delete('/customer/wishlist/destroy/{id}', 'WishListController@customerDestroy')->name('wishlist.destroy');

//checkout controller
Route::get('/checkout', 'CheckoutController@checkout')->name('checkout');
Route::post('/checkout/confirm', 'CheckoutController@checkoutConfirm')->name('checkout.confirm');
Route::post('/checkout/store', 'CheckoutController@checkoutStore')->name('checkout.store');


//invoice details route
Route::get('/invoice/details/{InvoiceNo}', 'InvoiceController@invoiceDetails')->name('invoice.details');

//search route
Route::get('/search', 'HomeController@search')->name('global.search');


//Registration ang login and forgot password  controller
Route::post('/send-otp', 'Auth\RegisterController@sendOtp')->name('send.otp');
Route::post('/send-otp-for-forgot', 'Auth\RegisterController@sendOtpForForgot')->name('send.otp.for.forgot');
Route::post('/forgot/password/change', 'Auth\RegisterController@forgotPasswordChange')->name('forgot.password.change');
Route::post('/send-verify', 'Auth\RegisterController@otpVerify')->name('send.verify');
Route::get('/registration-success', 'Auth\RegisterController@registrationSuccess')->name('registration.success');

Route::post('/register/with-offer', 'Auth\RegisterController@registrationWithOffer')->name('register.with.offer');
Route::post('/login/with-offer', 'Auth\LoginController@loginWithOffer')->name('login.with.offer');

Route::get('/product/with-offer/{id}', 'OfferProductController@productWithOffer')->name('product.with.offer');
Route::post('/start/claim', 'OfferProductController@startClaim')->name('start.claim');

Auth::routes();

//Route::group(['middleware' => ['auth']], function() {
//    Route::get('/user_dashboard', 'UserDashboardController@index')->name('user_dashboard');
//});


//customer route

Route::get('/customer/profile', 'CustomerDashboardController@customerProfile')->name('customer.profile');
Route::post('/customer/profile/update', 'CustomerDashboardController@customerProfileUpdate')->name('customer.profile.update');

Route::get('/customer/address', 'CustomerDashboardController@customerAddress')->name('customer.address');
Route::get('/customer/address/create', 'CustomerDashboardController@customerAddressCreate')->name('customer.address.create');
Route::post('/customer/address/store', 'CustomerDashboardController@customerAddressStore')->name('customer.address.store');
Route::get('/customer/address/edit/{id}', 'CustomerDashboardController@customerAddressEdit')->name('customer.address.edit');
Route::post('/customer/address/update/{id}', 'CustomerDashboardController@customerAddressUpdate')->name('customer.address.update');
Route::get('/customer/address/delete/{id}', 'CustomerDashboardController@customerAddressDelete')->name('customer.address.delete');

Route::post('/customer/store', 'CustomerDashboardController@customerStore')->name('customer.store');
Route::get('/customer/order', 'CustomerDashboardController@customerOrder')->name('customer.order');
Route::get('/customer/change-password', 'CustomerDashboardController@customerChangePassword')->name('customer.change.password');
Route::post('/customer/change-password', 'CustomerDashboardController@customerChangePasswordStore')->name('customer.change.password.store');

Route::get('/customer/invoice-print/{id}', 'CustomerDashboardController@customerInvoicePrint')->name('customer.invoice.print');


//product review rating
Route::post('/product/review-rating', 'ReviewRatingController@storeReviewRating')->name('store.review.rating');

//District Wise Thana
Route::post('district/wise/thana', 'CheckoutController@districtWiseThana')->name('district.wise.thana');

//apply coupon
Route::post('apply/coupon', 'OfferProductController@applyCoupon')->name('apply.coupon');


//google login
Route::get('/login/google/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/login/google/callback', 'Auth\LoginController@handleProviderCallback');

//guest order route
Route::post('/guest/order', 'GustOrderController@guestOrder')->name('guest.order');
Route::post('/guest/order/store', 'GustOrderController@guestOrderStore')->name('guest.checkout.store');
Route::get('/invoice/success', 'GustOrderController@invoiceSuccess')->name('invoice.success');

//track order route
Route::get('/order/track', 'OrderTrackController@orderTrack')->name('order.track');
Route::post('/order/track-post', 'OrderTrackController@orderTrackPost')->name('order.track.post');

Route::get('/offer-category','HomeController@offerCategory')->name('offer.category');
Route::get('/offer-category-details/{id?}','HomeController@offerDetails')->name('offer.details');

Route::get('/product-offer-details/{id?}', 'HomeController@productOfferDetails')->name('product.offer.details');

//Kids Educational Apps
Route::get('/kids-educational-apps', 'PagesController@kidsEducational')->name('kids.educational.app');

