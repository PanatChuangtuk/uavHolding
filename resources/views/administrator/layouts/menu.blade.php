<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme sidebar">
    <div class="app-brand demo">
        <a href="{{ route('administrator.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                {{-- <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"> --}}
                <defs>
                    <path
                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                        id="path-1"></path>
                    <path
                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                        id="path-3"></path>
                    <path
                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                        id="path-4"></path>
                    <path
                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                        id="path-5"></path>
                </defs>
                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                <mask id="mask-2" fill="white">
                                    <use xlink:href="#path-1"></use>
                                </mask>
                                <use fill="#696cff" xlink:href="#path-1"></use>
                                <g id="Path-3" mask="url(#mask-2)">
                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                </g>
                                <g id="Path-4" mask="url(#mask-2)">
                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                </g>
                            </g>
                            <g id="Triangle"
                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                <use fill="#696cff" xlink:href="#path-5"></use>
                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                            </g>
                        </g>
                    </g>
                </g>
                </svg>
            </span>
            <span class="app-brand-text demos menu-text fw-bold ms-2">Admin</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item {{ Route::is('administrator.dashboard*') ? 'active' : '' }}">
            <a href="{{ route('administrator.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div data-i18n="Basic"> Dashboard </div>
            </a>
        </li>
        @php
            use App\Helpers\PermissionHelper;
        @endphp

        @if (PermissionHelper::checkPermission('manage_banner') ||
                PermissionHelper::checkPermission('manage_about') ||
                PermissionHelper::checkPermission('manage_brand') ||
                PermissionHelper::checkPermission('manage_catalog') ||
                PermissionHelper::checkPermission('manage_common'))
            <li class="menu-item {{ $main_menu == 'website' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-dock-top"></i>
                    <div class="text-truncate" data-i18n="Layouts">Website</div>
                </a>

                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_banner'))
                        <li class="menu-item {{ Route::is('administrator.banner*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.banner') }}" class="menu-link">
                                <div class="text-truncate">Banner</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_about'))
                        <li class="menu-item {{ Route::is('administrator.about*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.about') }}" class="menu-link">
                                <div class="text-truncate">About</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_brand'))
                        <li class="menu-item {{ Route::is('administrator.brand*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.brand') }}" class="menu-link">
                                <div class="text-truncate">Our Brand</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_catalog'))
                        <li class="menu-item {{ Route::is('administrator.catalog*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.catalog') }}" class="menu-link">
                                <div class="text-truncate">Catalog</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_common'))
                        <li
                            class="menu-item {{ Route::is('administrator.common.edit') && Request::segment(4) == 1 ? 'active' : '' }}">
                            <a href="{{ route('administrator.common.edit', 1) }}" class="menu-link">
                                <div class="text-truncate">Service</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Route::is('administrator.common.edit') && Request::segment(4) == 2 ? 'active' : '' }}">
                            <a href="{{ route('administrator.common.edit', 2) }}" class="menu-link">
                                <div class="text-truncate">Privacy Policy</div>
                            </a>
                        </li>
                        <li
                            class="menu-item {{ Route::is('administrator.common.edit') && Request::segment(4) == 3 ? 'active' : '' }}">
                            <a href="{{ route('administrator.common.edit', 3) }}" class="menu-link">
                                <div class="text-truncate">Term & Conditions</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_contact') ||
                PermissionHelper::checkPermission('manage_list-contact') ||
                PermissionHelper::checkPermission('manage_social'))
            <li class="menu-item {{ $main_menu == 'contact' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-phone"></i>
                    <div class="text-truncate" data-i18n="Layouts">Contact</div>
                </a>

                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_contact'))
                        <li class="menu-item {{ Route::is('administrator.contact*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.contact.edit', 1) }}" class="menu-link">
                                <div class="text-truncate">Contact Us</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_list-contact'))
                        <li class="menu-item {{ Route::is('administrator.list-contact*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.list-contact') }}" class="menu-link">
                                <div class="text-truncate">Contact Us List</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_social'))
                        <li class="menu-item {{ Route::is('administrator.social*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.social') }}" class="menu-link">
                                <div class="text-truncate">Social</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_faq') ||
                PermissionHelper::checkPermission('manage_news') ||
                PermissionHelper::checkPermission('manage_testimonial'))
            <li class="menu-item {{ $main_menu == 'contents' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-news"></i>
                    <div class="text-truncate" data-i18n="Layouts">Contents</div>
                </a>

                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_faq'))
                        <li class="menu-item {{ Route::is('administrator.faq*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.faq') }}" class="menu-link">
                                <div class="text-truncate">Q & A</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_news'))
                        <li class="menu-item {{ Route::is('administrator.news*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.news') }}" class="menu-link">
                                <div class="text-truncate">News</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_testimonial'))
                        <li class="menu-item {{ Route::is('administrator.testimonial*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.testimonial') }}" class="menu-link">
                                <div class="text-truncate">Testimonial</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_brand-product') ||
                PermissionHelper::checkPermission('manage_product') ||
                PermissionHelper::checkPermission('manage_promotion') ||
                PermissionHelper::checkPermission('manage_recommend') ||
                PermissionHelper::checkPermission('manage_reviews') ||
                PermissionHelper::checkPermission('manage_model-product') ||
                PermissionHelper::checkPermission('manage_coupon') ||
                PermissionHelper::checkPermission('manage_promo-discount'))
            <li class="menu-item {{ $main_menu == 'products' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-store"></i>
                    <div class="text-truncate" data-i18n="Layouts">Products</div>
                </a>

                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_brand-product'))
                        <li class="menu-item {{ Route::is('administrator.brand-product*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.brand-product') }}" class="menu-link">
                                <div class="text-truncate">Brands</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_product'))
                        <li class="menu-item {{ Route::is('administrator.product*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.product') }}" class="menu-link">
                                <div class="text-truncate">Products</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_promotion'))
                        <li class="menu-item {{ Route::is('administrator.promotion*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.promotion') }}" class="menu-link">
                                <div class="text-truncate">Promotion</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_recommend'))
                        <li class="menu-item {{ Route::is('administrator.recommend*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.recommend') }}" class="menu-link">
                                <div class="text-truncate">Recommend</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_reviews'))
                        <li class="menu-item {{ Route::is('administrator.reviews*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.reviews') }}" class="menu-link">
                                <div class="text-truncate">Reviews</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_model-product'))
                        <li class="menu-item {{ Route::is('administrator.model-product*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.model-product') }}" class="menu-link">
                                <div class="text-truncate">Products Model</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_coupon'))
                        <li class="menu-item {{ Route::is('administrator.coupon*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.coupon') }}" class="menu-link">
                                <div class="text-truncate">Coupon Discount</div>
                            </a>
                        </li>
                    @endif

                    @if (PermissionHelper::checkPermission('manage_promo_discount'))
                        <li class="menu-item {{ Route::is('administrator.promo_discount*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.promo_discount') }}" class="menu-link">
                                <div class="text-truncate">Promotion Discount</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_member') || PermissionHelper::checkPermission('manage_group-member'))
            <li class="menu-item {{ $main_menu == 'customer' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div class="text-truncate" data-i18n="Layouts">Customer</div>
                </a>
                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_member'))
                        <li class="menu-item {{ Route::is('administrator.member*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.member') }}" class="menu-link">
                                <div data-i18n="Basic"> Customer List </div>
                            </a>
                        </li>
                    @endif
                    @if (PermissionHelper::checkPermission('manage_group-member'))
                        <li class="menu-item {{ Route::is('administrator.group-member*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.group-member') }}" class="menu-link">
                                <div data-i18n="Basic"> Customer Group </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif


        @if (PermissionHelper::checkPermission('manage_users') ||
                PermissionHelper::checkPermission('manage_roles') ||
                PermissionHelper::checkPermission('manage_permissions'))
            <li class="menu-item {{ $main_menu == 'admin' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-check-shield"></i>
                    <div class="text-truncate" data-i18n="Layouts">Admin</div>
                </a>
                <ul class="menu-sub ">
                    @if (PermissionHelper::checkPermission('manage_users'))
                        <li class="menu-item {{ Route::is('administrator.users*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.users') }}" class="menu-link">
                                <div data-i18n="Basic"> Admin List </div>
                            </a>
                        </li>
                    @endif
                    @if (PermissionHelper::checkPermission('manage_roles'))
                        <li class="menu-item {{ Route::is('administrator.roles*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.roles') }}" class="menu-link">
                                <div data-i18n="Basic"> Role </div>
                            </a>
                        </li>
                    @endif
                    @if (PermissionHelper::checkPermission('manage_permissions'))
                        <li class="menu-item {{ Route::is('administrator.permissions*') ? 'active' : '' }}">
                            <a href="{{ route('administrator.permissions') }}" class="menu-link">
                                <div data-i18n="Basic"> Permission </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_order'))

            @if (PermissionHelper::checkPermission('manage_approve_order'))
                <li class="menu-item {{ Route::is('administrator.approve-order*') ? 'active' : '' }}">
                    <a href="{{ route('administrator.approve-order') }}" class="menu-link">
                        <i class='menu-icon bx bx-credit-card'></i>
                        <div data-i18n="Basic"> Order Approve </div>
                    </a>
                </li>
            @endif

            <li class="menu-item {{ Route::is('administrator.order*') ? 'active' : '' }}">
                <a href="{{ route('administrator.order') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                    <div data-i18n="Basic"> Order </div>
                </a>
            </li>
        @endif

        @if (PermissionHelper::checkPermission('manage_seo'))
            <li class="menu-item {{ $main_menu == 'setting' ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bxs-cog"></i>
                    <div class="text-truncate" data-i18n="Layouts">Settting</div>
                </a>
                <ul class="menu-sub ">

                    <li class="menu-item {{ Route::is('administrator.seo*') ? 'active' : '' }}">
                        <a href="{{ route('administrator.seo') }}" class="menu-link">
                            <div data-i18n="Basic">SEO </div>
                        </a>
                    </li>

                </ul>
            </li>
        @endif
    </ul>
</aside>
<!-- / Menu -->
