<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <!--begin:Menu item-->
                <div class="app-brand demo">
                    {{-- <a href="{{ url('/admin') }}" class="app-brand-link">
                        <img src="{{ asset('app_logo.png') }}" width="100" alt="Logo">
                    </a> --}}

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
                        <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <!-- Dashboard -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.home') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.dashboard') }}</span>
                    </a>
                </div>

                <!-- Wallet Transactions -->
                {{-- <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/wallet-transactions') ? 'active' : '' }}" href="{{ route('admin.wallet-transactions.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-wallet fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.wallet-transactions') }}</span>
                    </a>
                </div> --}}

                <!-- Notifications -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/notifications*') ? 'active' : '' }}" href="{{ route('admin.notifications.send') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-notification-on fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title">رسائل الإشعارات (FCM)</span>
                    </a>
                </div>

                <!-- Captions -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/captions') ? 'active' : '' }}" href="{{ route('admin.captions.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-message-text-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.captions') }}</span>
                    </a>
                </div>

                <!-- Admin Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/admins*') || request()->is('admin/roles*') || request()->is('admin/audit-logs*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-profile-circle fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('cruds.admin.title') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/admins') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.list') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/admins/create') ? 'active' : '' }}" href="{{ route('admin.admins.create') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.create') }}</span>
                            </a>
                        </div>
                        @can('role_access')
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ trans('cruds.role.title') }}</span>
                            </a>
                        </div>
                        @endcan
                    </div>
                    <!--end:Menu sub-->
                </div>

                <!-- User Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/users*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-people fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('cruds.user.title') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/users') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.list') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/users/create') ? 'active' : '' }}" href="{{ route('admin.users.create') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.create') }}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <!-- Driver Management -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/drivers*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-steering-wheel fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('cruds.driver.title') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/drivers') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('cruds.driver.list_all') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/drivers/activated') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('cruds.driver.activated') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/drivers/deactivated') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('cruds.driver.deactivated') }}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <!-- Services -->
                @can('service_access')
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/services*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-car fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('cruds.service.title') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/services') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.list') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/services/create') ? 'active' : '' }}" href="{{ route('admin.services.create') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.create') }}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>
                @endcan

                <!-- Payments -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/payments*') ? 'here show' : '' }}">
                    <!--begin:Menu link-->
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.payments.title') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <!--end:Menu link-->
                    <!--begin:Menu sub-->
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/payments') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.transactions') }}</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->is('admin/payments/requests') ? 'active' : '' }}" href="{{ route('admin.payments.requests') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">{{ __('global.withdraw_requests') }}</span>
                            </a>
                        </div>
                    </div>
                    <!--end:Menu sub-->
                </div>

                <!-- Settings -->
                @can('setting_access')
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index', 1) }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-setting-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.settings') }}</span>
                    </a>
                </div>
                @endcan


                <!-- Countries -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/countries*') ? 'active' : '' }}" href="{{ route('admin.countries.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-flag fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('app.countries') }}</span>
                    </a>
                </div>

                <!-- Cities -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/cities*') ? 'active' : '' }}" href="{{ route('admin.cities.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-city fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('app.cities') }}</span>
                    </a>
                </div>

                <!-- Payment Methods -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/payment-methods*') ? 'active' : '' }}" href="{{ route('admin.payment-methods.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-credit-cart fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('app.payment_methods') }}</span>
                    </a>
                </div>



                <!-- Incomes -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/incomes*') ? 'active' : '' }}" href="{{ route('admin.incomes.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-simple fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.incomes') }}</span>
                    </a>
                </div>

                <!-- Expenses -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/expenses*') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-discount fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">المصروفات</span>
                    </a>
                </div>

                <!-- Chats -->
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/chats*') ? 'active' : '' }}" href="{{ route('admin.chats.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-message-text-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ trans('global.chats') }}</span>
                    </a>
                </div>


            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>