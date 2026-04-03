@extends('layouts.admin')

@section('title', $pageTitle)
@section('pageName', $pageTitle)

@section('breadcrumbs')
<li class="breadcrumb-item text-muted"> طرق الدفع </li>
<span class="bullet bg-gray-300 w-5px h-2px"></span>
<li class="breadcrumb-item text-dark">عرض الكل</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <form action="{{ route('admin.payment-methods.update') }}" method="POST" enctype="multipart/form-data" id="payment-methods-form">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            <i class="ki-duotone ki-wallet fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            {{ $pageTitle }}
                        </h3>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info d-flex align-items-center mb-5">
                        <i class="ki-duotone ki-information fs-3 me-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <div>
                            {{ __('Configure your payment gateway settings below. Changes will be saved immediately.') }}
                        </div>
                    </div>

                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6" id="paymentTabs">
                      @foreach ($payment_methods as $key => $inputs)
                        <li class="nav-item me-2">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $key }}" role="tab">
                                <i class="ki-duotone ki-wallet fs-2 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{ ucfirst($key) }}
                            </a>
                        </li>
                    @endforeach
                    </ul>

                    <div class="tab-content p-4 border border-top-0 rounded-bottom" id="paymentTabsContent">
                    @foreach ($payment_methods as $key => $inputs)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel">
                            <div class="row g-6">
                                @foreach ($inputs as $input => $value)
                                    @if ($input == 'logo' && $value)
                                        <div class="col-12 text-center mb-6">
                                            <div class="image-input image-input-outline" data-kt-image-input="true">
                                                <div class="image-input-wrapper w-150px h-150px" style="background-image: url({{ $value }})"></div>
                                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                                    data-kt-image-input-action="change"
                                                    data-bs-toggle="tooltip"
                                                    title="Change logo">
                                                    <i class="ki-outline ki-pencil fs-6"></i>
                                                    <input type="file" name="{{ $key . '[' . $input . ']' }}" accept=".png, .jpg, .jpeg" />
                                                </label>
                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                                                    data-kt-image-input-action="cancel"
                                                    data-bs-toggle="tooltip"
                                                    title="Cancel logo">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </span>
                                            </div>
                                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        </div>
                                    @endif

                                    @if ($input == 'enabled')
                                        <div class="col-md-6">
                                            <div class="form-check form-switch form-check-custom form-check-solid form-check-lg">
                                                <input class="form-check-input" type="checkbox" 
                                                    name="{{ $key . '[' . $input . ']' }}" 
                                                    id="{{ $key }}_enabled" 
                                                    {{ $value == 1 ? 'checked' : '' }} />
                                              <label class="form-check-label fs-5 fw-semibold" for="{{ $key }}_enabled">
    {{ __('Enable ' . ucfirst($key)) }}
</label>
                                                <div class="form-text text-muted">
                                                    {{ __('Toggle to enable/disable this payment method') }}
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($input != 'logo')
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <input type="{{ $input == 'logo' ? 'file' : 'text' }}"
                                                    name="{{ $key . '[' . $input . ']' }}"
                                                    id="{{ $key . '_' . $input }}"
                                                    class="form-control form-control-solid"
                                                    value="{{ $input != 'logo' ? $value : '' }}"
                                                    placeholder="{{ __('app.' . $input) }}"
                                                    {{ $input == 'name' ? 'readonly' : '' }}>
                                                <label for="{{ $key . '_' . $input }}" class="fs-6 fw-semibold text-gray-700">
                                                    {{ ucwords(str_replace('_', ' ', $input)) }}
                                                </label>
                                                @if($input != 'name')
                                                    <div class="form-text">
                                                        {{ __('Enter your ' . str_replace('_', ' ', $input) . ' for ' . ucfirst($key)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between align-items-center py-4">
                {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-active-light-primary">
                    <i class="ki-outline ki-arrow-left fs-3 me-2"></i> 
                    {{ __('Back to Dashboard') }}
                </a> --}}
                <button type="submit" class="btn btn-primary px-6">
                    <i class="ki-outline ki-save fs-3 me-2"></i> 
                    {{ __('global.save_changes') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .nav-line-tabs .nav-link {
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }
    .nav-line-tabs .nav-link.active {
        border-bottom: 3px solid #009EF7;
        font-weight: 600;
    }
    .form-control-solid {
        background-color: #f5f8fa;
        border-color: #f5f8fa;
    }
    .form-control-solid:focus {
        background-color: #fff;
        border-color: #009EF7;
    }
    .card {
        border-radius: 0.625rem;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }
    .tab-content {
        background-color: #fff;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('marketopia/js/tabs.js') }}"></script>
<script>
    // Initialize image input
    $('[data-kt-image-input="true"]').each(function() {
        const imageInput = new KTImageInput(this);
    });

    // Form validation
    $("#payment-methods-form").validate({
        rules: {
            // Add validation rules as needed
        },
        messages: {
            // Add custom messages
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        highlight: function(element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function(element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        }
    });

    // Show success message on save
    @if(session('success'))
        Swal.fire({
            text: "{{ session('success') }}",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
    @endif
</script>
@endpush