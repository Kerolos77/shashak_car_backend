@extends('layouts.app')

@section('content')
<div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-10 text-center text-success">
            {{ session('status') }}
        </div>
    @endif

    <form class="form w-100" method="POST" action="{{ route('login') }}" id="kt_sign_in_form" data-kt-redirect-url="">
        @csrf
         <div class="d-flex flex-center flex-column mb-7">
    <a href="{{ url('/') }}">
        <img alt="Logo" 
             src="{{ asset('app_logo.png') }}" 
             class="h-75px h-lg-100px w-auto" />
    </a>
</div>

        <div class="text-center mb-11">
            <h1 class="text-gray-900 fw-bolder mb-3">{{ __('global.login') }}</h1>
            {{-- <div class="text-gray-500 fw-semibold fs-6">Your Social Camypaigns</div> --}}
        </div>

        <!-- Separator -->
        <div class="separator separator-content my-14">
            <span class="w-125px text-gray-500 fw-semibold fs-7">{{ app()->getLocale()=='ar'?'مرحبا بالعوده':'Welcome Back' }}</span>
        </div>

        <!-- Email Input -->
        <div class="fv-row mb-8">
            <input id="email" 
                   class="form-control bg-transparent @error('email') is-invalid @enderror" 
                   type="email" 
                   name="email" 
                   placeholder="{{ __('global.login_email') }}"
                   value="{{ old('email') }}" 
                   required autofocus />
            @error('email')
                <div class="mt-2 text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Input -->
        <div class="fv-row mb-3">
            <input id="password" 
                   class="form-control bg-transparent @error('password') is-invalid @enderror"
                   type="password"
                   name="password"
                   placeholder="{{ __('global.login_password') }}"
                   required autocomplete="current-password" />
            @error('password')
                <div class="mt-2 text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
            <div class="form-check form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
                <label class="form-check-label" for="remember_me">
                    {{ __('global.remember_me') }}
                </label>
            </div>
            
            @if (Route::has('password.request'))
              <a href="" class="text-blueGray-200">
                                <small>{{ __('global.forgot_password') }}</small>
                            </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                <span class="indicator-label"> {{ __('global.login') }}</span>
                <span class="indicator-progress">
                    {{ __('Please wait...') }}
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('kt_sign_in_form').addEventListener('submit', function() {
        const submitButton = document.getElementById('kt_sign_in_submit');
        submitButton.disabled = true;
        submitButton.querySelector('.indicator-label').style.display = 'none';
        submitButton.querySelector('.indicator-progress').style.display = 'inline-block';
    });
</script>
@endsection