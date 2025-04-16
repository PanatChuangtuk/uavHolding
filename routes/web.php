<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RedirectIfNotLoggedInFrontEnd;
use App\Http\Controllers\{
    NewsController,
    IndexController,
    LocaleController,
    ServiceController,
    AboutController,
    ContactController,
    FaqController,
    PromotionController,
    CatalogController,
    PurchaseController,
    LoginController,
    RegisterController,
    ProfileController,
    AddressController,
    TaxController,
    CartController,
    ProductController,
    FavouriteController,
    ReviewsController,
    CouponController,
    NotificationController,
    TermController,
    PrivacyController,
    ChangePasswordController,
    TrackTraceController,
    ProductListController,
    TermUserController,
    RefundController,
    PrivacyUserController,
    CheckoutController
};

require base_path('routes/admin.php');
require base_path('routes/api.php');
Route::get('lang/{lang}', [LocaleController::class, 'setLocale'])->name('setLocale');
Route::prefix('{lang}')->group(function () {
    Route::get('about', [AboutController::class, 'aboutIndex'])->name('about');
    Route::get('cart', [CartController::class, 'cartIndex'])->name('cart.index');
    Route::get('contact', [ContactController::class, 'contactIndex'])->name('contact');
    Route::get('download-catalog/{id}', [CatalogController::class, 'downloadCatalog'])->name('download.catalog');
    Route::get('download', [CatalogController::class, 'catalogIndex'])->name('download');
    Route::get('faq', [FaqController::class, 'faqIndex'])->name('faq');
    Route::get('/', [IndexController::class, 'bannerShow'])->name('index');
    Route::get('login', [LoginController::class, 'loginIndex'])->name('login');
    Route::get('news-detail/{id}', [NewsController::class, 'newsDetail'])->name('news.detail');
    Route::get('news', [NewsController::class, 'newsIndex'])->name('news');
    Route::get('otp-forgot-password-login', [LoginController::class, 'dataForgotPassword'])->name('login.forgot.password');
    Route::get('otp-forgot-password', [LoginController::class, 'otpForgotPassword'])->name('forgot.password');
    Route::get('otp-verify-password-login', [LoginController::class, 'otpVerifyPassword'])->name('verify.password');
    Route::get('privacy-policy-user', [PrivacyUserController::class, 'privacyIndex'])->name('privacy.user');
    Route::get('product-detail/{id}', [ProductController::class, 'productDetail'])->name('product.detail');
    Route::get('product-list', [ProductListController::class, 'productListIndex'])->name('product.list');
    Route::get('product', [ProductController::class, 'productIndex'])->name('product');
    Route::get('promotion-detail/{id}', [PromotionController::class, 'promotionDetail'])->name('promotion.detail');
    Route::get('promotion', [PromotionController::class, 'promotionIndex'])->name('promotion');
    Route::get('register-otp', [RegisterController::class, 'registerOtpVerify'])->name('register.otp.verify');
    Route::get('register', [RegisterController::class, 'registerIndex'])->name('register');
    Route::get('return-refund/{id}', [RefundController::class, 'refundIndex'])->name('refund');
    Route::get('service', [ServiceController::class, 'serviceIndex'])->name('service');
    Route::get('set-new-password', [LoginController::class, 'resetPasswordIndex'])->name('login.reset.password');
    Route::get('term-condition-user', [TermUserController::class, 'termIndex'])->name('term.user');
    Route::get('term-condition', [TermController::class, 'termIndex'])->name('term');
    Route::get('track-trace', [TrackTraceController::class, 'trackTraceIndex'])->name('track.trace');
    Route::post('cart-add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('cart-delete/{id}', [CartController::class, 'deleteCart'])->name('cart.delete');
    Route::post('cart-remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');
    Route::post('login', [LoginController::class, 'submit'])->name('login.submit');
    Route::post('order', [CartController::class, 'order'])->name('order.submit');
    Route::post('order/submit/{id}', [CartController::class, 'orderAddress'])->name('cart.order.submit');
    Route::post('otp-forgot-password-login/submit', [LoginController::class, 'dataForgotPasswordSubmit'])->name('login.forgot.password.submit');
    Route::post('otp-forgot-password/submit', [LoginController::class, 'otpForgotPasswordSubmit'])->name('forgot.password.submit');
    Route::post('otp-verify-password-login/submit', [LoginController::class, 'otpVerifyPasswordSubmit'])->name('verify.password.submit');
    Route::post('product-review', [ProductController::class, 'productReviewSubmit'])->name('product.review.submit');
    Route::post('product/favourite/{id}', [ProductController::class, 'myFavourite'])->name('product.favourite');
    Route::post('product/submit', [ProductController::class, 'submit'])->name('product.submit');
    Route::post('register-otp/submit', [RegisterController::class, 'registerOtpVerifySubmit'])->name('register.otp.verify.submit');
    Route::post('register/submit', [RegisterController::class, 'submit'])->name('register.submit');
    Route::post('send-otp-password', [LoginController::class, 'sendRequestOtp'])->name('send.otp.password');
    Route::post('set-new-password/submit', [LoginController::class, 'resetPasswordSubmit'])->name('login.reset.password.submit');
    Route::post('contact/submit', [ContactController::class, 'submit'])->name('contact_us.submit');
    Route::post('notification/update/{id}', [NotificationController::class, 'notificationUpdate'])->name('notification.update');
    Route::middleware([RedirectIfNotLoggedInFrontEnd::class])->group(function () {
        Route::post('exit-checkout', [CheckoutController::class, 'exitCheckout'])->name('exit.checkout');
        Route::get('address', [AddressController::class, 'address'])->name('profile.address');
        Route::get('cart-address-detail/{id}', [AddressController::class, 'edit'])->name('profile.address.edit');
        Route::get('cart-address', [AddressController::class, 'cartAddress'])->name('profile.address.add');
        Route::post('cart-address-delete/{id}', [AddressController::class, 'delete'])->name('profile.address.delete');
        Route::get('cart-check-out/{id}', [CartController::class, 'cartCheckIndex'])->name('cart.check.index');
        Route::get('change-password', [ChangePasswordController::class, 'changePasswordIndex'])->name('change.password');
        Route::get('my-coupon', [CouponController::class, 'couponIndex'])->name('coupon');
        Route::post('my-coupon/claim', [CouponController::class, 'applyCoupon'])->name('coupon.claim');
        Route::get('my-favourite', [FavouriteController::class, 'favouriteIndex'])->name('favourite');
        Route::get('my-point', [CouponController::class, 'pointIndex'])->name('point');
        Route::get('my-purchase', [PurchaseController::class, 'purchaseIndex'])->name('purchase');
        Route::get('my-reviews', [ReviewsController::class, 'myReviewIndex'])->name('my.reviews');
        Route::get('notification', [NotificationController::class, 'notificationIndex'])->name('notification');
        Route::get('privacy-policy', [PrivacyController::class, 'privacyIndex'])->name('privacy');
        Route::get('profile', [ProfileController::class, 'profileIndex'])->name('profile');
        Route::get('request-full-tax-invoice-detail/{id}', [TaxController::class, 'edit'])->name('tax.edit');
        Route::get('request-full-tax-invoice', [TaxController::class, 'add'])->name('tax.add');
        Route::get('reviews', [ReviewsController::class, 'reviewsIndex'])->name('reviews');
        Route::get('set-new-password-2', [ProfileController::class, 'resetPasswordIndex'])->name('profile.reset.password');
        Route::get('tax-invoice', [TaxController::class, 'tax'])->name('tax');
        Route::post('cart-address/submit', [AddressController::class, 'submit'])->name('profile.address.submit');
        Route::post('cart-address/update/{id}', [AddressController::class, 'update'])->name('profile.address.update');
        Route::post('get-otp-request', [ChangePasswordController::class, 'getOtpRequest'])->name('get.otp.request');
        Route::post('logout', [ProfileController::class, 'logout'])->name('logout');
        Route::post('otp-request/submit', [ChangePasswordController::class, 'otpRequestSubmit'])->name('otp.request.submit');
        Route::post('profile', [ProfileController::class, 'submit'])->name('profile.submit');
        Route::post('request-full-tax-invoice/submit', [TaxController::class, 'submit'])->name('tax.submit');
        Route::post('request-full-tax-invoice/update/{id}', [TaxController::class, 'update'])->name('tax.update');
        Route::post('request-full-tax-invoice/delete/{id}', [TaxController::class, 'delete'])->name('tax.delete');
        Route::post('set-new-password-2/submit', [ProfileController::class, 'resetPasswordSubmit'])->name('profile.reset.password.submit');
        // Route::middleware('member.active')->group(function () {
        //     Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        // });
    });
});


Route::get('/', function () {
    return redirect(app()->getLocale() . '/ ');
})->where('any', '.*');
