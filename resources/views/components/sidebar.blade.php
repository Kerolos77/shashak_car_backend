<div class="overflow-hidden app-sidebar-menu flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Menu wrapper-->
        <div class="app-sidebar-wrapper">
            <!--begin::Scroll wrapper-->
            <div class="app-sidebar-menu-overflow scroll-y me-2 pe-2 py-5"
                 data-kt-scroll="true"
                 data-kt-scroll-activate="true"
                 data-kt-scroll-height="auto"
                 data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                 data-kt-scroll-wrappers="#kt_app_sidebar_menu"
                 data-kt-scroll-offset="5px"
                 data-kt-scroll-save-state="true">
                
                <!--begin::Menu-->
               <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                 id="#kt_app_sidebar_menu"
                 data-kt-menu="true"
                 data-kt-menu-expand="false">
                    
                  
                    
                    <!-- Dashboard -->
                    <div class="menu-item {{ request()->is('admin') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.home') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-home fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.dashboard') }}</span>
                        </a>
                    </div>

                    <!-- Shipping Orders -->
                    @can('order_access')
                    <div class="menu-item {{ request()->is('admin/shipping-orders*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.shipping-orders.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-truck fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __('admin.shipping_orders') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Wallet Transactions -->
                    {{-- <div class="menu-item {{ request()->is('admin/wallet-transactions') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.wallet-transactions.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-wallet fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.wallet-transactions') }}</span>
                        </a>
                    </div> --}}
                    
                    <!-- Notifications -->
                    @can('user_access')
                    <div class="menu-item {{ request()->is('admin/notifications*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.notifications.send') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-notification-on fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.notification_page.notifications') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Captions -->
                    @can('setting_access')
                    <div class="menu-item {{ request()->is('admin/captions') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.captions.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-message-text fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.captions') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Admin Section -->
                    @can('role_access')
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/admins*') || request()->is('admin/roles*') || request()->is('admin/audit-logs*') ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-user fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.admin.title') }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item {{ request()->is('admin/admins') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.admins.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.list') }}</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->is('admin/admins/create') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.admins.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.create') }}</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->is('admin/roles*') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.roles.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ trans('cruds.role.title') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan
                    
                    <!-- Users Section -->
                    @can('user_access')
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/users*') ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-user fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.user.title') }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item {{ request()->is('admin/users') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.users.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.list') }}</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->is('admin/users/create') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.users.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.create') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan
                    
                    <!-- Driver Applications Center -->
                    <div class="menu-item {{ request()->is('admin/driver-applications*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.driver-applications.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-id-card fs-2"></i>
                            </span>
                            <span class="menu-title">مركز مراجعة طلبات الانضمام والمستندات</span>
                            @php
                                $pendingAppsCount = \App\Models\DriverProfile::where('status', 'pending')->count();
                            @endphp
                            @if($pendingAppsCount > 0)
                                <span class="badge badge-warning ms-auto">{{ $pendingAppsCount }}</span>
                            @endif
                        </a>
                    </div>

                    <!-- Drivers Section -->
                    @can('driver_user_access')
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/drivers*') ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-car fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.driver.title') }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item {{ request()->is('admin/drivers') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.drivers.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('cruds.driver.list_all') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan

                    <!-- Manual Trip Assignment Section -->
                    @can('order_access')
                    <div class="menu-item {{ request()->is('admin/manual-assign*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.orders.manual_index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-people fs-2"></i>
                            </span>
                            <span class="menu-title">{{ __('admin.manual_assignment') }}</span>
                        </a>
                    </div>

                    <!-- Live Fleet Driver Map -->
                    <div class="menu-item {{ request()->is('admin/fleet-map*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.fleet-map.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-geolocation fs-2"></i>
                            </span>
                            <span class="menu-title">خريطة تتبع أسطول السائقين</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Services Section -->
                    @can('service_access')
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/services*') ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-car fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.service.title') }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item {{ request()->is('admin/services') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.services.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.list') }}</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->is('admin/services/create') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.services.create') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.create') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan
                    
                    <!-- Payments Section -->
                    @can('payment_access')
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/payments*') ? 'here show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-outline ki-wallet fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.payments.title') }}</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item {{ request()->is('admin/payments') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.payments.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.transactions') }}</span>
                                </a>
                            </div>
                            <div class="menu-item {{ request()->is('admin/payments/requests') ? 'here' : '' }}">
                                <a class="menu-link" href="{{ route('admin.payments.requests') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">{{ __('global.withdraw_requests') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan
                    
                    <!-- Settings -->
                    @can('setting_access')
                    <div class="menu-item {{ request()->is('admin/settings*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.settings.index', 1) }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-setting-2 fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.settings') }}</span>
                        </a>
                    </div>

                    <!-- Dispatch & Ban Settings -->
                    <div class="menu-item {{ request()->is('admin/dispatch-settings*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.dispatch-settings.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-geolocation fs-2"></i>
                            </span>
                            <span class="menu-title">إعدادات التوزيع والحظر</span>
                        </a>
                    </div>

                    <!-- SMS Settings & Logs -->
                    <div class="menu-item {{ request()->is('admin/sms-settings*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.sms-settings.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-sms fs-2"></i>
                            </span>
                            <span class="menu-title">إدارة ورسائل SMS</span>
                        </a>
                    </div>
                    @endcan

                    <!-- Gamification -->
                    @can('gamification_access')
                    <div class="menu-item {{ request()->is('admin/gamification*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.gamification.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-medal-star fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.gamification.title') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Promo Coupons -->
                    @can('coupon_access')
                    <div class="menu-item {{ request()->is('admin/coupons*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.coupons.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-discount fs-2"></i>
                            </span>
                            <span class="menu-title">أكواد الكوبونات والخصومات</span>
                        </a>
                    </div>
                    @endcan

                    <!-- Support Tickets -->
                    @can('faq_access')
                    <div class="menu-item {{ request()->is('admin/tickets*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.tickets.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-message-question fs-2"></i>
                            </span>
                            <span class="menu-title">تذاكر الشكاوى والدعم الفني</span>
                        </a>
                    </div>
                    @endcan

                    <!-- Shop Packages -->
                    @can('package_access')
                    <div class="menu-item {{ request()->is('admin/packages*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.packages.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-shop fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('cruds.package.title') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Countries & Cities -->
                    @can('setting_access')
                    <div class="menu-item {{ request()->is('admin/countries*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.countries.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-flag fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('app.countries') }}</span>
                        </a>
                    </div>
                    
                    <div class="menu-item {{ request()->is('admin/cities*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.cities.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-geolocation fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('app.cities') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Payment Methods -->
                    <div class="menu-item {{ request()->is('admin/payment-methods*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.payment-methods.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-credit-cart fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('app.payment_methods') }}</span>
                        </a>
                    </div>
                    

                    
                    <!-- Incomes -->
                    @can('income_access')
                    <div class="menu-item {{ request()->is('admin/incomes*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.incomes.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-chart-line fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.incomes') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Expenses -->
                    @can('expense_access')
                    <div class="menu-item {{ request()->is('admin/expenses*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.expenses.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-discount fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.expenses') }}</span>
                        </a>
                    </div>
                    @endcan
                    
                    <!-- Chats -->
                    @can('chat_access')
                    <div class="menu-item {{ request()->is('admin/chats*') ? 'here show' : '' }}">
                        <a class="menu-link" href="{{ route('admin.chats.index') }}">
                            <span class="menu-icon">
                                <i class="ki-outline ki-messages fs-2"></i>
                            </span>
                            <span class="menu-title">{{ trans('global.chats') }}</span>
                        </a>
                    </div>
                    @endcan
                    

                    
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::Sidebar Container-->
</div>