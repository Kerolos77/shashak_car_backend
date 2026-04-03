<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Metronic Head Content -->
    <base href="../../../" />
    <meta name="description" content="The most advanced Bootstrap 5 Admin Theme" />
    <meta name="keywords" content="metronic, bootstrap, admin template" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    
    <!-- Metronic Styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
    <!-- Theme mode setup -->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <!-- Background image -->
    <style>
        body { background-image: url('{{ asset("assets/media/auth/bg4.jpg") }}'); }
        [data-bs-theme="dark"] body { background-image: url('{{ asset("assets/media/auth/bg4-dark.jpg") }}'); }
    </style>

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <!-- Left Side (Logo and Title) -->
           <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
    <div class="d-flex flex-center flex-lg-start flex-column">
        <!-- Logo -->
     
        <!-- Title -->
        {{-- <h2 class="text-white fw-normal m-0">Branding tools designed for your business</h2> --}}
    </div>
</div>

            <!-- Right Side (Slot for content) -->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Metronic Scripts -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form[data-kt-redirect-url]');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.setAttribute('data-kt-indicator', 'on');
                        submitButton.disabled = true;
                    }
                });
            });
        });
    </script>
</body>
</html>