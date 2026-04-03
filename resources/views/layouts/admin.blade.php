<!DOCTYPE html>

<html lang="en" dir="{{ app()->getLocale() == 'ar'?'rtl':'ltr' }}">
<!--begin::Head-->

<head>
    <title>
        @yield('title')
    </title>
    <meta charset="utf-8" />

@stack('styles')
    <meta name="viewport" content="width=device-width, initial-scale=0.75, maximum-scale=1.0, user-scalable=no">

    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
@if(app()->getLocale()=='en')
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	@else
    
	  <link href="{{ asset('assets/plugins/global/plugins.bundle.rtl.css') }}" rel="stylesheet" type="text/css"/>
                            <link href="{{ asset('assets/css/style.bundle.rtl.css') }}" rel="stylesheet" type="text/css"/>
							@endif
    <style>
        /* Language Switching Improvements */
        .app-navbar .menu-sub .menu-link.active {
            background-color: var(--kt-primary-light);
            color: var(--kt-primary);
        }
        
        .app-navbar .menu-sub .menu-link:hover {
            background-color: var(--kt-primary-light);
            color: var(--kt-primary);
            transition: all 0.3s ease;
        }
        
        .app-navbar .menu-title {
            font-weight: 600;
        }
        
        /* Language indicator styling */
        .language-indicator {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* RTL Support for Language Menu */
        [dir="rtl"] .app-navbar .menu-sub {
            text-align: right;
        }
        
        [dir="rtl"] .app-navbar .menu-sub .menu-link {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .app-navbar .menu-sub .symbol {
            margin-left: 0.75rem;
            margin-right: 0;
        }
        
        /* User menu improvements */
        .app-navbar .symbol-label {
            font-weight: 700;
        }
        
        /* Sign out button styling */
        .app-navbar .menu-link button {
            width: 100%;
            text-align: left;
        }
        
        [dir="rtl"] .app-navbar .menu-link button {
            text-align: right;
        }
    </style>
    
    @livewireStyles
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
      var themeMode;
      if (document.documentElement) {
        if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
          themeMode =
            document.documentElement.getAttribute("data-bs-theme-mode");
        } else {
          if (localStorage.getItem("data-bs-theme") !== null) {
            themeMode = localStorage.getItem("data-bs-theme");
          } else {
            themeMode = defaultThemeMode;
          }
        }
        if (themeMode === "system") {
          themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches
            ? "dark"
            : "light";
        }
        document.documentElement.setAttribute("data-bs-theme", themeMode);
      }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
           <x-nav />
            <!--end::Header-->
            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    <!--begin::Logo-->
                    <div class="px-6 app-sidebar-logo" id="kt_app_sidebar_logo">
                        <!--begin::Logo image-->
                        <a href="{{ url('/') }}">
                                        {{-- <img src="{{ asset('app_logo.png') }}" width="100" alt="Logo"> --}}

                            <img alt="Logo" src="{{ asset('app_logo.png') }}"
                                class="h-60px app-sidebar-logo-default" style=""/>
                            <img alt="Logo" src="{{ asset('app_logo.png') }}"
                                class="h-20px app-sidebar-logo-minimize" />
                        </a>
                        <!--end::Logo image-->
                        <!--begin::Sidebar toggle-->
                        <!--begin::Minimized sidebar setup:
                         if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                           1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                            2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                            3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                         4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
                           }
                           -->
                        <div id="kt_app_sidebar_toggle"
                            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
                            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
                            data-kt-toggle-name="app-sidebar-minimize">
                            <i class="rotate-180 ki-duotone ki-black-left-line fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <!--end::Sidebar toggle-->
                    </div>
                    <!--end::Logo-->
                    <!--begin::sidebar menu-->
                    <x-sidebar />
                    <!--end::sidebar menu-->
                    <!--begin::Footer-->
                    {{-- <div class="px-6 pt-2 pb-6 app-sidebar-footer flex-column-auto" id="kt_app_sidebar_footer">
                        <a href="{{ url('/') }}"
                            class="px-0 overflow-hidden btn btn-flex flex-center btn-custom btn-primary text-nowrap h-40px w-100"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
                            title="200+ in-house components and 3rd-party plugins">
                            <span class="btn-label">Go To Site</span>
                            <i class="m-0 ki-duotone ki-document btn-icon fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                    </div> --}}
                    <!--end::Footer-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->

                        <!--end::Toolbar-->
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <!--begin::Content container-->
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                <!--begin::Row-->

@yield('content')

                            <!--end::Content container-->
                            </div>
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->
                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        <!--begin::Footer container-->
                        <div
                            class="py-3 app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack">
                            <!--begin::Copyright-->
                            <div class="order-2 text-gray-900 order-md-1">
                                <span class="text-muted fw-semibold me-1">2024&copy;</span>
                                <a href="https://keenthemes.com" target="_blank"
                                    class="text-gray-800 text-hover-primary">Keenthemes</a>
                            </div>
                            <!--end::Copyright-->
                            <!--begin::Menu-->
                            <ul class="order-1 menu menu-gray-600 menu-hover-primary fw-semibold">
                                <li class="menu-item">
                                    <a href="https://keenthemes.com" target="_blank" class="px-2 menu-link">About</a>
                                </li>
                                <li class="menu-item">
                                    <a href="https://devs.keenthemes.com" target="_blank"
                                        class="px-2 menu-link">Support</a>
                                </li>
                                <li class="menu-item">
                                    <a href="https://1.envato.market/EA4JP" target="_blank"
                                        class="px-2 menu-link">Purchase</a>
                                </li>
                            </ul>
                            <!--end::Menu-->
                        </div>
                        <!--end::Footer container-->
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>


    {{-- @include('admin.layouts.charts') --}}
    <!--end::Modal - Invite Friend-->
    <!--end::Modals-->
    <!--begin::Javascript-->
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!-- Vendors Javascript (used for this page only) -->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>

<!-- External JS Libraries -->


<!-- Datatables Plugin -->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->

<!-- Custom Javascript (used for this page only) -->
<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/create-app.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/new-target.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(vendorId) {
        // SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('delete-form-' + vendorId).submit();
            }
        });
    }
    // Global success alert function
function showSuccessAlert(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: message,
        showConfirmButton: false,
        timer: 1500
    });
}

// Global error alert function
function showErrorAlert(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        showConfirmButton: true
    });
}

</script>
@stack('scripts')
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    @livewireScripts
</body>
<!--end::Body-->

</html>
