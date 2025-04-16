<?php

use App\Http\Controllers\ERPWebhookLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AddressLocationController, ProductApiController, PostBackApiController, NotificationController, TrackApiController};

Route::prefix('erp-webhook')->group(function () {
    Route::controller(ERPWebhookLogController::class)->group(function () {
        Route::post('/', 'index');
        Route::patch('/update/{id}', 'update');
        Route::delete('/delete/{id}', 'delete');
    });
});
Route::get('notification/count', [NotificationController::class, 'getNotificationCount'])
    ->name('notification.count');

Route::controller(AddressLocationController::class)->group(function () {
    Route::get('/get-provinces', 'getProvinces');
    Route::get('/get-districts/{province_id}', 'getDistricts');
    Route::get('/get-subdistricts/{district_id}', 'getSubdistricts');
});
Route::controller(ProductApiController::class)->group(function () {
    Route::get('/get-products', 'getProducts');
    Route::get('/get-brands', 'getBrands');
    Route::get('/get-category', 'getCategory');
    Route::get('/get-model-single', 'getModelSingle');
    Route::get('/get-category-single', 'getCategorySingle');
    Route::get('/get-brands-single', 'getBrandsSingle');
    Route::get('/get-types-single', 'getTypesSingle');
    Route::get('/get-model-product', 'getProductModel');
    Route::get('/get-coupon-product', 'getUsedCoupons');
    Route::get('/get-coupon-discount', 'getCouponDiscount');
    // Route::get('/get-cart-info', 'getCartInfo');
    // Route::get('/get-use-points', 'getUsePoints');
    // Route::get('/get-test', 'testApi');
});

Route::controller(PostBackApiController::class)->group(function () {
    Route::post('/get-po-back', 'handlePoPostback');
    Route::post('/get-post-back', 'handlePostback');
});

Route::controller(TrackApiController::class)->group(function () {
    Route::post('/get-track-show', 'trackShow');
});


// Route::prefix('dashboard')->group(function () {
//     Route::controller(DashboardController::class)->group(function () {
//         Route::get('/total-sales', 'totalSales');
//         Route::get('/total-orders', 'totalOrders');
//         Route::get('/sales-overview', 'salesOverview');
//         Route::get('/revenue-by-month', 'revenueByMonth');
//         Route::get('/order-statistics', 'orderStatistics');
//         Route::get('/customer-graph', 'customerGraph');
//         Route::get('/top-products', 'topProducts');
//         Route::get('/latest-orders', 'latestOrders');
//     });
// });
