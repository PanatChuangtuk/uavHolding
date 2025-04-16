<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CkeditorController;
use App\Http\Middleware\{CheckPermission, RedirectIfNotLoggedInBackEnd};
use App\Http\Controllers\Administrator\{
    AboutController,
    AuthController,
    BannerController,
    BrandController,
    CatalogController,
    CommonController,
    ContactController,
    DashboardController,
    FaqController,
    NewsController,
    PermissionController,
    ProductController,
    PromotionController,
    RoleController,
    SocialController,
    TestimonialController,
    UserController,
    ProductModelController,
    ProductBrandController,
    RecommendController,
    ContactUsController,
    MemberController,
    MemberGroupController,
    OrderController,
    PromotionDiscountController,
    CouponDiscountController,
    ReviewsController,
    OrderApproveController,
    SeoController
};


Route::prefix('administrator')->group(function () {
    // Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('administrator.login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('administrator.login');
    // });

    Route::middleware([RedirectIfNotLoggedInBackEnd::class])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('administrator.dashboard');
        Route::get('/logout', [AuthController::class, 'logout'])->name('administrator.logout');
        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('administrator.ckeditor.upload');

        Route::group(['prefix' => 'news', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_news']], function () {
            Route::get('/', [NewsController::class, 'index'])->name('news');
            Route::get('/add', [NewsController::class, 'add'])->name('news.add');
            Route::post('/submit', [NewsController::class, 'submit'])->name('news.submit');
            Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
            Route::post('/update/{id}', [NewsController::class, 'update'])->name('news.update');
            Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
            Route::post('/bulk-delete', [NewsController::class, 'bulkDelete'])->name('news.bulk.delete');
            Route::post('image/{id}', [NewsController::class, 'deleteImage'])->name('news.delete.image');
            Route::post('/notifications/{id}', [NewsController::class, 'notifications'])->name('news.notifications.show');
        });

        // Banner Group
        Route::group(['prefix' => 'banner', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_banner']], function () {
            Route::get('/', [BannerController::class, 'index'])->name('banner');
            Route::get('/add', [BannerController::class, 'add'])->name('banner.add');
            Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
            Route::post('/submit', [BannerController::class, 'submit'])->name('banner.submit');
            Route::post('/update/{id}', [BannerController::class, 'update'])->name('banner.update');
            Route::delete('/{id}', [BannerController::class, 'destroy'])->name('banner.destroy');
            Route::post('/bulk-delete', [BannerController::class, 'bulkDelete'])->name('banner.bulk.delete');
            Route::post('image/{id}', [BannerController::class, 'deleteImage'])->name('banner.delete.image');
        });

        // About Group
        Route::group(['prefix' => 'about', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_about']], function () {
            Route::get('/', [AboutController::class, 'index'])->name('about');
            Route::get('/add', [AboutController::class, 'add'])->name('about.add');
            Route::get('/edit/{id}', [AboutController::class, 'edit'])->name('about.edit');
            Route::post('/submit', [AboutController::class, 'submit'])->name('about.submit');
            Route::post('/update/{id}', [AboutController::class, 'update'])->name('about.update');
            Route::delete('/{id}', [AboutController::class, 'destroy'])->name('about.destroy');
            Route::post('/bulk-delete', [AboutController::class, 'bulkDelete'])->name('about.bulk.delete');
            Route::post('image/{id}', [AboutController::class, 'deleteImage'])->name('about.delete.image');
        });

        // Testimonial Group
        Route::group(['prefix' => 'testimonial', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_testimonial']], function () {
            Route::get('/', [TestimonialController::class, 'index'])->name('testimonial');
            Route::get('/add', [TestimonialController::class, 'add'])->name('testimonial.add');
            Route::get('/edit/{id}', [TestimonialController::class, 'edit'])->name('testimonial.edit');
            Route::post('/submit', [TestimonialController::class, 'submit'])->name('testimonial.submit');
            Route::post('/update/{id}', [TestimonialController::class, 'update'])->name('testimonial.update');
            Route::delete('/{id}', [TestimonialController::class, 'destroy'])->name('testimonial.destroy');
            Route::post('/bulk-delete', [TestimonialController::class, 'bulkDelete'])->name('testimonial.bulk.delete');
            Route::post('image/{id}', [TestimonialController::class, 'deleteImage'])->name('testimonial.delete.image');
        });

        // Brand Group
        Route::group(['prefix' => 'brand', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_brand']], function () {
            Route::get('/', [BrandController::class, 'index'])->name('brand');
            Route::get('/add', [BrandController::class, 'add'])->name('brand.add');
            Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
            Route::post('/submit', [BrandController::class, 'submit'])->name('brand.submit');
            Route::post('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
            Route::delete('/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
            Route::post('/bulk-delete', [BrandController::class, 'bulkDelete'])->name('brand.bulk.delete');
            Route::post('image/{id}', [BrandController::class, 'deleteImage'])->name('brand.delete.image');
        });

        // Catalog Group
        Route::group(['prefix' => 'catalog', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_catalog']], function () {
            Route::get('/', [CatalogController::class, 'index'])->name('catalog');
            Route::get('/add', [CatalogController::class, 'add'])->name('catalog.add');
            Route::get('/edit/{id}', [CatalogController::class, 'edit'])->name('catalog.edit');
            Route::post('/submit', [CatalogController::class, 'submit'])->name('catalog.submit');
            Route::post('/update/{id}', [CatalogController::class, 'update'])->name('catalog.update');
            Route::delete('/{id}', [CatalogController::class, 'destroy'])->name('catalog.destroy');
            Route::post('/bulk-delete', [CatalogController::class, 'bulkDelete'])->name('catalog.bulk.delete');
            Route::post('image/{id}', [CatalogController::class, 'deleteImage'])->name('catalog.delete.image');
            Route::post('file/{id}', [CatalogController::class, 'deleteFile'])->name('catalog.delete.file');
        });

        Route::group(['prefix' => 'faq', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_faq']], function () {
            Route::get('/', [FaqController::class, 'index'])->name('faq');
            Route::get('/add', [FaqController::class, 'add'])->name('faq.add');
            Route::get('/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
            Route::post('/submit', [FaqController::class, 'submit'])->name('faq.submit');
            Route::post('/update/{id}', [FaqController::class, 'update'])->name('faq.update');
            Route::delete('/{id}', [FaqController::class, 'destroy'])->name('faq.destroy');
            Route::post('/bulk-delete', [FaqController::class, 'bulkDelete'])->name('faq.bulk.delete');
            Route::post('image/{id}', [FaqController::class, 'deleteImage'])->name('faq.delete.image');
        });

        Route::group(['prefix' => 'promotion', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_promotion']], function () {
            Route::get('/', [PromotionController::class, 'index'])->name('promotion');
            Route::get('/add', [PromotionController::class, 'add'])->name('promotion.add');
            Route::get('/edit/{id}', [PromotionController::class, 'edit'])->name('promotion.edit');
            Route::post('/submit', [PromotionController::class, 'submit'])->name('promotion.submit');
            Route::post('/update/{id}', [PromotionController::class, 'update'])->name('promotion.update');
            Route::delete('/{id}', [PromotionController::class, 'destroy'])->name('promotion.destroy');
            Route::post('/bulk-delete', [PromotionController::class, 'bulkDelete'])->name('promotion.bulk.delete');
            Route::post('image/{id}', [PromotionController::class, 'deleteImage'])->name('promotion.delete.image');
            Route::post('/notifications/{id}', [PromotionController::class, 'notifications'])->name('promotion.notifications.show');
        });

        Route::group(['prefix' => 'contact', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_contact']], function () {
            Route::get('/', [ContactController::class, 'index'])->name('contact');
            Route::get('/add', [ContactController::class, 'add'])->name('contact.add');
            Route::get('/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
            Route::post('/submit', [ContactController::class, 'submit'])->name('contact.submit');
            Route::post('/update/{id}', [ContactController::class, 'update'])->name('contact.update');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('contact.destroy');
            Route::post('/bulk-delete', [ContactController::class, 'bulkDelete'])->name('contact.bulk.delete');
            Route::post('image/{id}', [ContactController::class, 'deleteImage'])->name('contact.delete.image');
        });

        Route::group(['prefix' => 'list-contact', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_list-contact']], function () {
            Route::get('/', [ContactUsController::class, 'index'])->name('list-contact');
            Route::get('/add', [ContactUsController::class, 'add'])->name('list-contact.add');
            Route::get('/edit/{id}', [ContactUsController::class, 'edit'])->name('list-contact.edit');
            Route::post('/submit', [ContactUsController::class, 'submit'])->name('list-contact.submit');
            Route::post('/update/{id}', [ContactUsController::class, 'update'])->name('list-contact.update');
            Route::delete('/{id}', [ContactUsController::class, 'destroy'])->name('list-contact.destroy');
            Route::post('/bulk-delete', [ContactUsController::class, 'bulkDelete'])->name('list-contact.bulk.delete');
            Route::post('image/{id}', [ContactUsController::class, 'deleteImage'])->name('list-contact.delete.image');
            Route::post('/update-status/{id}', [ContactUsController::class, 'updateStatus'])->name('update.status');
        });

        Route::group(['prefix' => 'common', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_common']], function () {
            Route::get('/', [CommonController::class, 'index'])->name('common');
            Route::get('/add', [CommonController::class, 'add'])->name('common.add');
            Route::get('/edit/{id}', [CommonController::class, 'edit'])->name('common.edit');
            Route::post('/update/{id}', [CommonController::class, 'update'])->name('common.update');
            Route::delete('/{id}', [CommonController::class, 'destroy'])->name('common.destroy');
            Route::post('/bulk-delete', [CommonController::class, 'bulkDelete'])->name('common.bulk.delete');
            Route::post('/submit', [CommonController::class, 'submit'])->name('common.submit');
        });

        Route::group(['prefix' => 'social', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_social']], function () {
            Route::get('/', [SocialController::class, 'index'])->name('social');
            Route::get('/add', [SocialController::class, 'add'])->name('social.add');
            Route::get('/edit/{id}', [SocialController::class, 'edit'])->name('social.edit');
            Route::post('/submit', [SocialController::class, 'submit'])->name('social.submit');
            Route::post('/update/{id}', [SocialController::class, 'update'])->name('social.update');
            Route::delete('/{id}', [SocialController::class, 'destroy'])->name('social.destroy');
            Route::post('/bulk-delete', [SocialController::class, 'bulkDelete'])->name('social.bulk.delete');
            Route::post('image/{id}', [SocialController::class, 'deleteImage'])->name('social.delete.image');
        });

        Route::group(['prefix' => 'users', 'as' => 'administrator.'], function () {
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users.edit')->middleware([CheckPermission::class . ':edit_profile']);
            Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update')->middleware([CheckPermission::class . ':edit_profile']);
        });

        Route::group(['prefix' => 'users', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_users']], function () {
            Route::get('/', [UserController::class, 'index'])->name('users');
            Route::get('/add', [UserController::class, 'add'])->name('users.add');
            Route::post('/submit', [UserController::class, 'submit'])->name('users.submit');
            Route::post('/users/{id}/change-role', [UserController::class, 'changeRole'])->name('users.change-role');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::post('/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk.delete');
        });

        Route::group(['prefix' => 'roles', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_roles']], function () {
            Route::get('/', [RoleController::class, 'index'])->name('roles');
            Route::get('/add', [RoleController::class, 'add'])->name('roles.add');
            Route::post('/submit', [RoleController::class, 'submit'])->name('roles.submit');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
            Route::post('/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk.delete');
        });

        Route::group(['prefix' => 'permissions', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_permissions']], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('permissions');
            Route::get('/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
            Route::put('/update', [PermissionController::class, 'update'])->name('permissions.update');
        });

        Route::group(['prefix' => 'product', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_product']], function () {
            Route::get('/', [ProductController::class, 'index'])->name('product');
            Route::get('/add', [ProductController::class, 'add'])->name('product.add');
            Route::post('/submit', [ProductController::class, 'submit'])->name('product.submit');
            Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product.destroy');
            Route::post('/bulk-delete', [ProductController::class, 'bulkDelete'])->name('product.bulk.delete');
            Route::get('/import', [ProductController::class, 'importPage'])->name('product.import');
            Route::get('/export', [ProductController::class, 'exportPage'])->name('product.export');
            Route::post('/import/submit', [ProductController::class, 'import'])->name('product.import.submit');
            Route::post('/export/submit', [ProductController::class, 'export'])->name('product.export.submit');
            Route::post('/detonate', [ProductController::class, 'detonate'])->name('detonate');
        });

        Route::group(['prefix' => 'model-product', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_model-product']], function () {
            Route::get('/', [ProductModelController::class, 'index'])->name('model-product');
            Route::get('/add', [ProductModelController::class, 'add'])->name('model-product.add');
            Route::post('/submit', [ProductModelController::class, 'submit'])->name('model-product.submit');
            Route::get('/edit/{id}', [ProductModelController::class, 'edit'])->name('model-product.edit');
            Route::post('/update/{id}', [ProductModelController::class, 'update'])->name('model-product.update');
            Route::delete('/{id}', [ProductModelController::class, 'destroy'])->name('model-product.destroy');
            Route::post('/bulk-delete', [ProductModelController::class, 'bulkDelete'])->name('model-product.bulk.delete');
            Route::post('image/{id}', [ProductModelController::class, 'deleteImage'])->name('model-product.delete.image');
        });

        Route::group(['prefix' => 'brand-product', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_brand-product']], function () {
            Route::get('/', [ProductBrandController::class, 'index'])->name('brand-product');
            Route::get('/add', [ProductBrandController::class, 'add'])->name('brand-product.add');
            Route::post('/submit', [ProductBrandController::class, 'submit'])->name('brand-product.submit');
            Route::get('/edit/{id}', [ProductBrandController::class, 'edit'])->name('brand-product.edit');
            Route::post('/update/{id}', [ProductBrandController::class, 'update'])->name('brand-product.update');
            Route::delete('/{id}', [ProductBrandController::class, 'destroy'])->name('brand-product.destroy');
            Route::post('/bulk-delete', [ProductBrandController::class, 'bulkDelete'])->name('brand-product.bulk.delete');
            Route::post('image/{id}', [ProductBrandController::class, 'deleteImage'])->name('brand-product.delete.image');
        });

        Route::group(['prefix' => 'recommend', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_recommend']], function () {
            Route::get('/', [RecommendController::class, 'index'])->name('recommend');
            Route::get('/add', [RecommendController::class, 'add'])->name('recommend.add');
            Route::post('/submit', [RecommendController::class, 'submit'])->name('recommend.submit');
            Route::get('/edit/{id}', [RecommendController::class, 'edit'])->name('recommend.edit');
            Route::post('/update/{id}', [RecommendController::class, 'update'])->name('recommend.update');
            Route::delete('/{id}', [RecommendController::class, 'destroy'])->name('recommend.destroy');
            Route::post('/bulk-delete', [RecommendController::class, 'bulkDelete'])->name('recommend.bulk.delete');
        });
        Route::group(['prefix' => 'reviews', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_reviews']], function () {
            Route::get('/', [ReviewsController::class, 'index'])->name('reviews');
            Route::post('/update', [ReviewsController::class, 'update'])->name('reviews.update');
        });

        Route::group(['prefix' => 'member', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_member']], function () {
            Route::get('/', [MemberController::class, 'index'])->name('member');
            Route::get('/add', [MemberController::class, 'add'])->name('member.add');
            Route::post('/submit', [MemberController::class, 'submit'])->name('member.submit');
            Route::get('/edit/{id}', [MemberController::class, 'edit'])->name('member.edit');
            Route::post('/update/{id}', [MemberController::class, 'update'])->name('member.update');
            Route::delete('/{id}', [MemberController::class, 'destroy'])->name('member.destroy');
            Route::post('/bulk-delete', [MemberController::class, 'bulkDelete'])->name('member.bulk.delete');
        });

        Route::group(['prefix' => 'group-member', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_group-member']], function () {
            Route::get('/', [MemberGroupController::class, 'index'])->name('group-member');
            Route::get('/add', [MemberGroupController::class, 'add'])->name('group-member.add');
            Route::post('/submit', [MemberGroupController::class, 'submit'])->name('group-member.submit');
            Route::get('/edit/{id}', [MemberGroupController::class, 'edit'])->name('group-member.edit');
            Route::post('/update/{id}', [MemberGroupController::class, 'update'])->name('group-member.update');
            Route::delete('/{id}', [MemberGroupController::class, 'destroy'])->name('group-member.destroy');
            Route::post('/bulk-delete', [MemberGroupController::class, 'bulkDelete'])->name('group-member.bulk.delete');
        });

        Route::group(['prefix' => 'order', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_order']], function () {
            Route::get('/', [OrderController::class, 'index'])->name('order');
            Route::get('/add', [OrderController::class, 'add'])->name('order.add');
            Route::post('/submit', [OrderController::class, 'submit'])->name('order.submit');
            Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
            Route::post('/update', [OrderController::class, 'update'])->name('order.update');
            Route::delete('/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
            Route::post('/bulk-delete', [OrderController::class, 'bulkDelete'])->name('order.bulk.delete');
            Route::post('/update-status', [OrderController::class, 'updateStatus'])->name('order.status.update');
        });

        Route::group(['prefix' => 'promo_discount', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_promo_discount']], function () {
            Route::get('/', [PromotionDiscountController::class, 'index'])->name('promo_discount');
            Route::get('/add', [PromotionDiscountController::class, 'add'])->name('promo_discount.add');
            Route::post('/submit', [PromotionDiscountController::class, 'submit'])->name('promo_discount.submit');
            Route::get('/edit/{id}', [PromotionDiscountController::class, 'edit'])->name('promo_discount.edit');
            Route::post('/update/{id}', [PromotionDiscountController::class, 'update'])->name('promo_discount.update');
            Route::delete('/{id}', [PromotionDiscountController::class, 'destroy'])->name('promo_discount.destroy');
            Route::post('/bulk-delete', [PromotionDiscountController::class, 'bulkDelete'])->name('promo_discount.bulk.delete');
            Route::post('/notifications/{id}', [PromotionDiscountController::class, 'notifications'])->name('promo_discount.notifications.show');
        });
        Route::group(['prefix' => 'coupon', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_coupon']], function () {
            Route::get('/', [CouponDiscountController::class, 'index'])->name('coupon');
            Route::get('/add', [CouponDiscountController::class, 'add'])->name('coupon.add');
            Route::post('/submit', [CouponDiscountController::class, 'submit'])->name('coupon.submit');
            Route::get('/edit/{id}', [CouponDiscountController::class, 'edit'])->name('coupon.edit');
            Route::post('/update/{id}', [CouponDiscountController::class, 'update'])->name('coupon.update');
            Route::delete('/{id}', [CouponDiscountController::class, 'destroy'])->name('coupon.destroy');
            Route::post('/bulk-delete', [CouponDiscountController::class, 'bulkDelete'])->name('coupon.bulk.delete');
            Route::post('/notifications/{id}', [CouponDiscountController::class, 'notifications'])->name('coupon.notifications.show');
        });
        Route::group(['prefix' => 'approve-order', 'as' => 'administrator.', 'middleware' => [CheckPermission::class . ':manage_approve_order']], function () {
            Route::get('/', [OrderApproveController::class, 'index'])->name('approve-order');
            Route::get('/edit/{id}', [OrderApproveController::class, 'edit'])->name('approve-order.edit');
            Route::post('/update', [OrderApproveController::class, 'update'])->name('approve-order.update');
            Route::delete('/{id}', [OrderApproveController::class, 'destroy'])->name('approve-order.destroy');
        });
        Route::group(['prefix' => 'seo', 'as' => 'administrator.'], function () {
            Route::get('/', [SeoController::class, 'index'])->name('seo');
            Route::get('/edit/{id}', [SeoController::class, 'edit'])->name('seo.edit');
            Route::post('/update/{id}', [SeoController::class, 'update'])->name('seo.update');
            // Route::delete('/{id}', [OrderApproveController::class, 'destroy'])->name('approve-order.destroy');
        });
    });
});
