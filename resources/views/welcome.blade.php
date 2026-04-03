<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light-style layout-navbar-fixed layout-menu-fixed "
    dir="{{ app()->getLocale() == 'en' ? 'ltr' : 'rtl' }}" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-starter">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ env('APP_NAME') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/css/rtl/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/css/rtl/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/css/demo.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/typeahead-js/typeahead.css" />
    <script src="{{ asset('/') }}assets/vendor/js/template-customizer.js"></script>
    <script src="{{ asset('/') }}assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('/') }}assets/js/config.js"></script>
    @livewireStyles
    @stack('styles')

</head>

<body>
    <div class="container">
        {!! $page->content_en !!}
    </div>


    <script src="{{ asset('assets') }}/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('assets') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('assets') }}/vendor/js/menu.js"></script>
    <script src="{{ asset('assets') }}/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="{{ asset('assets') }}/js/main.js"></script>
    @livewireStyles
    @stack('scripts')
    
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
