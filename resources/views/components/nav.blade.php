<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
    data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
    data-kt-sticky-animation="false">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
        id="kt_app_header_container">
        <!--begin::Sidebar mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>
        <!--end::Sidebar mobile toggle-->
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="index.html" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/media/logos/default-small.svg') }}" class="h-30px" />
            </a>
        </div>
        <!--end::Mobile logo-->
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <!--begin::Menu wrapper-->
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
                data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
                data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
                data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
                data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <!--begin::Menu-->
                <div class="px-2 my-5 menu menu-rounded menu-column menu-lg-row my-lg-0 align-items-stretch fw-semibold px-lg-0"
                    id="kt_app_header_menu" data-kt-menu="true">
                    <!--begin:Menu item-->
                    <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
                        class="menu-item here show menu-here-bg menu-lg-down-accordion me-0 me-lg-2">
                        <!--begin:Menu link-->
                        <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">
                                    @yield('title_crud', 'لوحة التحكم')
                                </h1>

                                <!-- Separator -->
                                <span class="h-20px border-gray-300 border-start mx-4 d-none d-md-flex"></span>

                                <!-- Breadcrumbs -->
                                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                                    @hasSection('breadcrumbs')
                                        @yield('breadcrumbs')
                                    @endif
                                </ul>
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->

                    <!--end:Menu item-->
                    <!--begin:Menu item-->

                    <!--end:Menu item-->
                    <!--begin:Menu item-->

                    <!--end:Menu item-->
                    <!--begin:Menu item-->

                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
            <!--begin::Navbar-->
            <div class="flex-shrink-0 app-navbar">
                <!--begin::Search-->

                <!--end::Search-->
                <!--begin::Activities-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    <!--begin::Drawer toggle-->
                    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                        id="kt_activities_toggle">
                        <i class="ki-duotone ki-messages fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </div>
                    <!--end::Drawer toggle-->
                </div>
                <!--end::Activities-->
                <!--begin::Notifications-->

                <!--end::Notifications-->
                <!--begin::Chat-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    <!--begin::Menu wrapper-->
                    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative"
                        id="kt_drawer_chat_toggle">
                        <i class="ki-duotone ki-message-text-2 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        <span
                            class="top-0 bullet bullet-dot bg-success h-6px w-6px position-absolute translate-middle start-50 animation-blink"></span>
                    </div>
                    <!--end::Menu wrapper-->
                </div>
                <!--end::Chat-->
                <!--begin::My apps links-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    <!--begin::Menu wrapper-->
                    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-element-11 fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </div>
                    <!--begin::My apps-->

                    <!--end::My apps-->
                    <!--end::Menu wrapper-->
                </div>
                <!--end::My apps links-->
                <!--begin::Theme V1/V2 Swapper-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    @if(session()->get('admin_theme') === 'v2')
                        <a href="{{ route('admin.switch-theme', 'v1') }}" 
                           class="btn btn-sm btn-light-warning fw-bold px-3 d-flex align-items-center gap-2 border border-warning border-opacity-30" 
                           title="{{ app()->getLocale() == 'ar' ? 'الرجوع إلى المظهر التقليدي V1' : 'Switch back to classic V1 look' }}">
                            <span class="bullet bg-warning w-8px h-8px rounded-circle"></span>
                            <span class="fs-8 text-uppercase">V1 Classic</span>
                        </a>
                    @else
                        <a href="{{ route('admin.switch-theme', 'v2') }}" 
                           class="btn btn-sm btn-light-success fw-bold px-3 d-flex align-items-center gap-2 border border-success border-opacity-30" 
                           title="{{ app()->getLocale() == 'ar' ? 'تفعيل المظهر الفاخر V2' : 'Activate premium V2 dashboard' }}">
                            <span class="bullet bg-success w-8px h-8px rounded-circle"></span>
                            <span class="fs-8 text-uppercase fw-bold text-success">V2 Premium</span>
                        </a>
                    @endif
                </div>
                <!--end::Theme V1/V2 Swapper-->

                <!--begin::Theme mode-->
                <div class="app-navbar-item ms-1 ms-md-4">
                    <!--begin::Menu toggle-->
                    <a href="#"
                        class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
                        data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-night-day theme-light-show fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                            <span class="path6"></span>
                            <span class="path7"></span>
                            <span class="path8"></span>
                            <span class="path9"></span>
                            <span class="path10"></span>
                        </i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </a>
                    <!--begin::Menu toggle-->
                    <!--begin::Menu-->
                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px"
                        data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <!--begin::Menu item-->
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-night-day fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                        <span class="path7"></span>
                                        <span class="path8"></span>
                                        <span class="path9"></span>
                                        <span class="path10"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-moon fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="px-3 my-0 menu-item">
                            <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-screen fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Theme mode-->
                <!--begin::User menu-->
                <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-35px"
                        data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
                        data-kt-menu-placement="bottom-end">
                        <div class="symbol-label bg-light-primary text-primary fs-6 fw-bold">
                            {{ substr(auth()->user()->full_name, 0, 1) }}
                        </div>
                    </div>
                    <!--begin::User account menu-->
                    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px"
                        data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="px-3 menu-item">
                            <div class="px-3 menu-content d-flex align-items-center">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-50px symbol-circle me-3">
                                    <div class="symbol-label bg-light-primary text-primary fs-6 fw-bold">
                                        {{ substr(auth()->user()->full_name, 0, 1) }}
                                    </div>
                                </div>
                                <!--begin::Username-->
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ auth()->user()->name }}
                                        <span class="px-2 py-1 badge badge-light-success fw-bold fs-8 ms-2">
                                            {{ app()->getLocale() == 'ar' ? 'مدير' : 'Admin' }}
                                        </span>
                                    </div>
                                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                        {{ auth()->user()->email }}
                                    </a>
                                    <span class="fw-semibold text-muted fs-8 mt-1">
                                        {{ app()->getLocale() == 'ar' ? 'آخر تسجيل دخول: الآن' : 'Last login: Now' }}
                                    </span>
                                </div>
                                <!--end::Username-->
                            </div>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu separator-->
                        <div class="my-2 separator"></div>
                        <!--end::Menu separator-->
                        <!--begin::Menu item-->


                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="px-5 menu-item" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                            data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                            <a href="#" class="px-5 menu-link d-flex align-items-center">
                                <i class="ki-outline ki-global fs-6 me-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <span class="menu-title position-relative">
                                    {{ app()->getLocale() == 'ar' ? 'اللغة' : 'Language' }}
                                    <span class="px-3 py-2 rounded fs-8 bg-light position-absolute translate-middle-y top-50 end-0 d-flex align-items-center">
                                        {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                                        <img class="w-15px h-15px rounded-1 ms-2"
                                            src="{{ asset(app()->getLocale() == 'ar' ? 'assets/media/flags/saudi-arabia.svg' : 'assets/media/flags/united-states.svg') }}"
                                            alt="{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}" />
                                    </span>
                                </span>
                            </a>

                            <!--begin::Menu sub-->
                            <div class="py-4 menu-sub menu-sub-dropdown w-200px">
                                <!-- English -->
                                <div class="px-3 menu-item">
                                    <a href="{{ route('lang.switch', 'en') }}"
                                        class="px-5 menu-link d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1"
                                                src="{{ asset('assets/media/flags/united-states.svg') }}" 
                                                alt="English" />
                                        </span>
                                        <span class="menu-title">English</span>
                                        @if(app()->getLocale() == 'en')
                                            <i class="ki-outline ki-check fs-6 ms-auto text-success"></i>
                                        @endif
                                    </a>
                                </div>
                                <!-- Arabic -->
                                <div class="px-3 menu-item">
                                    <a href="{{ route('lang.switch', 'ar') }}"
                                        class="px-5 menu-link d-flex align-items-center {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                        <span class="symbol symbol-20px me-4">
                                            <img class="rounded-1"
                                                src="{{ asset('assets/media/flags/saudi-arabia.svg') }}" 
                                                alt="العربية" />
                                        </span>
                                        <span class="menu-title">العربية</span>
                                        @if(app()->getLocale() == 'ar')
                                            <i class="ki-outline ki-check fs-6 ms-auto text-success"></i>
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <!--end::Menu sub-->
                        </div>

                        <!--end::Menu item-->
                        <!--begin::Menu item-->

                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="px-5 menu-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-5 menu-link d-flex align-items-center"
                                    style="background: none; border: none; padding: 0; margin: 0; cursor: pointer; width: 100%;">
                                    <i class="ki-outline ki-exit-right fs-6 me-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Sign Out' }}
                                </button>
                            </form>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::User account menu-->
                    <!--end::Menu wrapper-->
                </div>
                <!--end::User menu-->
                <!--begin::Header menu toggle-->
                <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                    <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px"
                        id="kt_app_header_menu_toggle">
                        <i class="ki-duotone ki-element-4 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <!--end::Header menu toggle-->
                <!--begin::Aside toggle-->
                <!--end::Header menu toggle-->
            </div>
            <!--end::Navbar-->
        </div>
        <!--end::Header wrapper-->
    </div>
    <!--end::Header container-->
</div>