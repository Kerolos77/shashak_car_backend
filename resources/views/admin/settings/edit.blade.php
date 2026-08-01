@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">إعدادات النظام</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">الإعدادات العامة</li>
@endsection

@section('content')
@section('title', $pageTitle)
@section('pageName', $pageTitle)

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script>
        $(function() {
            $(window).ready(function() {
                var select2 = $('.select2');
                if (select2.length) {
                    select2.each(function() {
                        var $this = $(this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder: 'Select value',
                            dropdownParent: $this.parent()
                        });
                    });
                }
            });

            // Unified Add button with SweetAlert modal
            $(document).on('click', '.addValue', function() {
                Swal.fire({
                    title: '{{ __("global.add_value") ?? "إضافة قيمة" }}',
                    text: '{{ __("global.choose_value_type") ?? "اختر نوع القيمة التي تريد إضافتها" }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("cruds.setting.fields.increase") ?? "قيمة ثابتة" }}',
                    cancelButtonText: '{{ __("cruds.setting.fields.percentage_increase") ?? "نسبة مئوية" }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        addStaticValue();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        addPercentageValue();
                    }
                });
            });

            function addStaticValue() {
                var count = $(document).find('input[name="increase[]"]').length;
                var staticSection = $('#static-values-section');
                staticSection.append(`<div><div class="row align-items-center mb-3"><div class="col-md-9"><label class="form-label" for="form-repeater-${count}-1">{{ __('cruds.setting.fields.increase') }}</label><input type="number" value="" step="0.5" name="increase[]" id="form-repeater-${count}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.increase') }}" /></div><div class="col-md-3 mt-4"><button class="btn btn-danger w-100 data-repeater-delete" type="button"><i class="ti ti-x me-1"></i>حذف</button></div></div></div>`);
            }

            function addPercentageValue() {
                var count = $(document).find('input[name="percentage_increase[]"]').length;
                var percentageSection = $('#percentage-values-section');
                percentageSection.append(`<div><div class="row align-items-center mb-3"><div class="col-md-9"><label class="form-label" for="form-repeater-percentage-${count}-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'النسبة المئوية' }}</label><div class="input-group"><input type="number" value="" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-${count}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'النسبة المئوية' }}" /><span class="input-group-text">%</span></div></div><div class="col-md-3 mt-4"><button class="btn btn-danger w-100 data-repeater-delete-percentage" type="button"><i class="ti ti-x me-1"></i>حذف</button></div></div></div>`);
            }

            $(document).on('click', '.data-repeater-delete', function() {
                $(this).closest('.row').parent().remove();
            });

            $(document).on('click', '.data-repeater-delete-percentage', function() {
                $(this).closest('.row').parent().remove();
            });

            // Handle active type change
            $('input[name="active_type"]').on('change', function() {
                var selectedType = $(this).val();
                if (selectedType === 'increase') {
                    $('#static-values-section').show();
                    $('#percentage-values-section').hide();
                } else if (selectedType === 'percentage_increase') {
                    $('#static-values-section').hide();
                    $('#percentage-values-section').show();
                }
            });

            // Initialize on page load
            var activeType = $('input[name="active_type"]:checked').val();
            if (activeType === 'increase') {
                $('#static-values-section').show();
                $('#percentage-values-section').hide();
            } else if (activeType === 'percentage_increase') {
                $('#static-values-section').hide();
                $('#percentage-values-section').show();
            } else {
                $('#static-values-section').show();
                $('#percentage-values-section').show();
            }
        });
    </script>
@endpush

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        
        <!-- Header Card -->
        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-setting-2 fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">إعدادات النظام العامة والأسعار</h2>
                        <div class="text-muted fs-6">التحكم الفوري في تسعير الرحلات، العمولات، الذكاء الاصطناعي، وروابط التطبيقات والتواصل.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-6">
                
                <form action="{{ route('admin.settings.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-6 fs-6 fw-bold">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#tab_pricing_commission">
                                <i class="ki-outline ki-dollar fs-4 me-2"></i>الأسعار والعمولات والمحفظة
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_general_links">
                                <i class="ki-outline ki-link fs-4 me-2"></i>التواصل وروابط التطبيقات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_ai_verification">
                                <i class="ki-outline ki-technology-4 fs-4 me-2"></i>التحقق بالذكاء الاصطناعي (Gemini AI)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_feature_toggles">
                                <i class="ki-outline ki-toggle-on fs-4 me-2"></i>تفعيل وتعطيل الأنظمة (ON / OFF)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab_cloud_expenses">
                                <i class="ki-outline ki-chart-line-down fs-4 me-2"></i>إعدادات السحابة والمصروفات
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="settingsTabContent">

                        <!-- TAB 1: Pricing & Commission -->
                        <div class="tab-pane fade show active" id="tab_pricing_commission">
                            
                            <div class="card border border-gray-300 shadow-none mb-6">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-calculator fs-3 me-2 text-primary"></i> نظام الزيادة الديناميكية والتسعير
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold mb-3">{{ __('cruds.setting.fields.active_type') }}</label>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-custom form-check-solid p-3 border rounded bg-light">
                                                    <input class="form-check-input" type="radio" name="active_type" id="active_increase" value="increase" {{ $row->active_type == 'increase' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-gray-800 ms-2" for="active_increase">
                                                        {{ __('cruds.setting.fields.increase') }} (مبالغ قيمة ثابتة ج.م)
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-check-custom form-check-solid p-3 border rounded bg-light">
                                                    <input class="form-check-input" type="radio" name="active_type" id="active_percentage" value="percentage_increase" {{ $row->active_type == 'percentage_increase' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-gray-800 ms-2" for="active_percentage">
                                                        {{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }} (نسبة مئوية %)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Static Values Section -->
                                    <div class="form-repeater mb-4" id="static-values-section">
                                        <h5 class="fw-bold mb-3 text-primary">{{ __('cruds.setting.fields.increase') }} (مبالغ قيمة ثابتة)</h5>
                                        @if ($row->increase != null)
                                            @foreach ($row->increase as $c => $item)
                                                <div>
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-md-9">
                                                            <label class="form-label" for="form-repeater-{{ $c }}-1">{{ __('cruds.setting.fields.increase') }}</label>
                                                            <input type="number" value="{{ $item }}" step="0.5" name="increase[]" id="form-repeater-{{ $c }}-1" class="form-control form-control-solid" placeholder="{{ __('cruds.setting.fields.increase') }}" />
                                                        </div>
                                                        <div class="col-md-3 mt-4">
                                                            <button class="btn btn-danger w-100 data-repeater-delete" type="button"> 
                                                                <i class="ti ti-x me-1"></i>حذف
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div>
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-md-9">
                                                        <label class="form-label" for="form-repeater-0-1">{{ __('cruds.setting.fields.increase') }}</label>
                                                        <input type="number" value="5" step="0.5" name="increase[]" id="form-repeater-0-1" class="form-control form-control-solid" placeholder="{{ __('cruds.setting.fields.increase') }}" />
                                                    </div>
                                                    <div class="col-md-3 mt-4">
                                                        <button class="btn btn-danger w-100 data-repeater-delete" type="button"> 
                                                            <i class="ti ti-x me-1"></i>حذف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Percentage Values Section -->
                                    <div class="form-repeater mb-4" id="percentage-values-section">
                                        <h5 class="fw-bold mb-3 text-primary">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }} (نسب مئوية)</h5>
                                        @if ($row->percentage_increase != null && count($row->percentage_increase) > 0)
                                            @foreach ($row->percentage_increase as $index => $item)
                                                <div>
                                                    <div class="row align-items-center mb-3">
                                                        <div class="col-md-9">
                                                            <label class="form-label" for="form-repeater-percentage-{{ $index }}-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</label>
                                                            <div class="input-group">
                                                                <input type="number" value="{{ $item }}" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-{{ $index }}-1" class="form-control form-control-solid" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}" />
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-4">
                                                            <button class="btn btn-danger w-100 data-repeater-delete-percentage" type="button"> 
                                                                <i class="ti ti-x me-1"></i>حذف
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div>
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-md-9">
                                                        <label class="form-label" for="form-repeater-percentage-0-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</label>
                                                        <div class="input-group">
                                                            <input type="number" value="5" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-0-1" class="form-control form-control-solid" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}" />
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mt-4">
                                                        <button class="btn btn-danger w-100 data-repeater-delete-percentage" type="button"> 
                                                            <i class="ti ti-x me-1"></i>حذف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <button class="btn btn-light-primary fw-bold addValue" type="button">
                                        <i class="ki-outline ki-plus fs-4 me-1"></i> إضافة قيمة زيادة جديدة
                                    </button>
                                </div>
                            </div>

                            <div class="card border border-gray-300 shadow-none">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-wallet fs-3 me-2 text-success"></i> عمولات النظام وحدود المحفظة
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="row g-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-min_withdraw">{{ __('cruds.setting.fields.min_withdraw') }}</label>
                                            <input type="number" step="0.5" value="{{ $row->min_withdraw }}" name="min_withdraw" class="form-control form-control-solid" id="basic-default-min_withdraw" placeholder="{{ __('cruds.setting.fields.min_withdraw') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-min_deposit">{{ __('cruds.setting.fields.min_deposit') }}</label>
                                            <input type="number" step="0.5" value="{{ $row->min_deposit }}" name="min_deposit" class="form-control form-control-solid" id="basic-default-min_deposit" placeholder="{{ __('cruds.setting.fields.min_deposit') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-request_price">{{ __('cruds.setting.fields.request_price') }}</label>
                                            <input type="number" step="0.5" value="{{ $row->request_price }}" name="request_price" class="form-control form-control-solid" id="basic-default-request_price" placeholder="{{ __('cruds.setting.fields.request_price') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-referral_bonus">{{ __('cruds.setting.fields.referral_bonus') }}</label>
                                            <input type="number" step="0.5" value="{{ $row->referral_bonus }}" name="referral_bonus" class="form-control form-control-solid" id="basic-default-referral_bonus" placeholder="{{ __('cruds.setting.fields.referral_bonus') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-commission_percentage">{{ __('cruds.setting.fields.commission_percentage') }} (%)</label>
                                            <input type="number" step="0.5" value="{{ $row->commission_percentage }}" name="commission_percentage" class="form-control form-control-solid" id="basic-default-commission_percentage" placeholder="{{ __('cruds.setting.fields.commission_percentage') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-min_driver_wallet_for_shipping">الحد الأدنى لمحفظة السائق لرحلات الشحن (ج.م)</label>
                                            <input type="number" step="0.5" value="{{ $row->min_driver_wallet_for_shipping }}" name="min_driver_wallet_for_shipping" class="form-control form-control-solid" id="basic-default-min_driver_wallet_for_shipping" placeholder="الحد الأدنى لمحفظة السائق لرحلات الشحن">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 2: General & Social Links -->
                        <div class="tab-pane fade" id="tab_general_links">
                            
                            <div class="card border border-gray-300 shadow-none mb-6">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-share fs-3 me-2 text-info"></i> روابط منصات التواصل الاجتماعي
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-facebook">{{ __('cruds.setting.fields.facebook') }}</label>
                                            <input type="url" value="{{ $row->facebook }}" name="facebook" class="form-control form-control-solid" id="basic-default-facebook" placeholder="{{ __('cruds.setting.fields.facebook') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-youtube">{{ __('cruds.setting.fields.youtube') }}</label>
                                            <input type="url" value="{{ $row->youtube }}" name="youtube" class="form-control form-control-solid" id="basic-default-youtube" placeholder="{{ __('cruds.setting.fields.youtube') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-linkedin">{{ __('cruds.setting.fields.linkedin') }}</label>
                                            <input type="url" value="{{ $row->linkedin }}" name="linkedin" class="form-control form-control-solid" id="basic-default-linkedin" placeholder="{{ __('cruds.setting.fields.linkedin') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-twitter">{{ __('cruds.setting.fields.twitter') }}</label>
                                            <input type="url" value="{{ $row->twitter }}" name="twitter" class="form-control form-control-solid" id="basic-default-twitter" placeholder="{{ __('cruds.setting.fields.twitter') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-tiktok">{{ __('cruds.setting.fields.tiktok') }}</label>
                                            <input type="url" value="{{ $row->tiktok }}" name="tiktok" class="form-control form-control-solid" id="basic-default-tiktok" placeholder="{{ __('cruds.setting.fields.tiktok') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-link_1">{{ __('cruds.setting.fields.link_1') }}</label>
                                            <input type="url" value="{{ $row->link_1 }}" name="link_1" class="form-control form-control-solid" id="basic-default-link_1" placeholder="{{ __('cruds.setting.fields.link_1') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-link_2">{{ __('cruds.setting.fields.link_2') }}</label>
                                            <input type="url" value="{{ $row->link_2 }}" name="link_2" class="form-control form-control-solid" id="basic-default-link_2" placeholder="{{ __('cruds.setting.fields.link_2') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-link_3">{{ __('cruds.setting.fields.link_3') }}</label>
                                            <input type="url" value="{{ $row->link_3 }}" name="link_3" class="form-control form-control-solid" id="basic-default-link_3" placeholder="{{ __('cruds.setting.fields.link_3') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border border-gray-300 shadow-none">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-apple fs-3 me-2 text-dark"></i> روابط متاجر التطبيقات والإيميلات
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-play_store_url">{{ __('cruds.setting.fields.play_store_url') }}</label>
                                            <input type="url" value="{{ $row->play_store_url }}" name="play_store_url" class="form-control form-control-solid" id="basic-default-play_store_url" placeholder="{{ __('cruds.setting.fields.play_store_url') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="basic-default-app_store_url">{{ __('cruds.setting.fields.app_store_url') }}</label>
                                            <input type="url" value="{{ $row->app_store_url }}" name="app_store_url" class="form-control form-control-solid" id="basic-default-app_store_url" placeholder="{{ __('cruds.setting.fields.app_store_url') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-email_1">{{ __('cruds.setting.fields.email_1') }}</label>
                                            <input type="email" value="{{ $row->email_1 }}" name="email_1" class="form-control form-control-solid" id="basic-default-email_1" placeholder="{{ __('cruds.setting.fields.email_1') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-email_2">{{ __('cruds.setting.fields.email_2') }}</label>
                                            <input type="email" value="{{ $row->email_2 }}" name="email_2" class="form-control form-control-solid" id="basic-default-email_2" placeholder="{{ __('cruds.setting.fields.email_2') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold" for="basic-default-email_3">{{ __('cruds.setting.fields.email_3') }}</label>
                                            <input type="email" value="{{ $row->email_3 }}" name="email_3" class="form-control form-control-solid" id="basic-default-email_3" placeholder="{{ __('cruds.setting.fields.email_3') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 3: AI & Verification -->
                        <div class="tab-pane fade" id="tab_ai_verification">
                            
                            <div class="card border border-gray-300 shadow-none">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-technology-4 fs-3 me-2 text-primary"></i> إعدادات محرك الذكاء الاصطناعي (Gemini AI Vision)
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label fw-bold" for="basic-default-gemini_api_key">مفتاح API الخاص بالتحقق بالذكاء الاصطناعي (Gemini API Key)</label>
                                            <input type="text" value="{{ $row->gemini_api_key }}" name="gemini_api_key" class="form-control form-control-solid" id="basic-default-gemini_api_key" placeholder="أدخل مفتاح Gemini API هنا">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold" for="basic-default-gemini_model">اسم نموذج الذكاء الاصطناعي المستخدم (Gemini Model Name)</label>
                                            <select name="gemini_model" class="form-select form-select-solid" id="basic-default-gemini_model">
                                                <option value="gemini-3.5-flash" {{ ($row->gemini_model ?? 'gemini-3.5-flash') == 'gemini-3.5-flash' ? 'selected' : '' }}>Gemini 3.5 Flash (الأحدث والأنسب للتحقق السريع)</option>
                                                <option value="gemini-3.5-pro" {{ ($row->gemini_model ?? '') == 'gemini-3.5-pro' ? 'selected' : '' }}>Gemini 3.5 Pro (الأقوى للأعمال المعقدة والذكاء العالي)</option>
                                                <option value="gemini-3.1-flash" {{ ($row->gemini_model ?? '') == 'gemini-3.1-flash' ? 'selected' : '' }}>Gemini 3.1 Flash (مستقر وسريع)</option>
                                                <option value="gemini-3.1-flash-lite" {{ ($row->gemini_model ?? '') == 'gemini-3.1-flash-lite' ? 'selected' : '' }}>Gemini 3.1 Flash-Lite (خفيف وسريع جداً للضغط العالي)</option>
                                                <option value="gemini-3.1-pro" {{ ($row->gemini_model ?? '') == 'gemini-3.1-pro' ? 'selected' : '' }}>Gemini 3.1 Pro (ذكي ومناسب للعمليات الطويلة والمنطق المعقد)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- TAB 4: System Feature ON/OFF Toggles -->
                        <div class="tab-pane fade" id="tab_feature_toggles">
                            <div class="card border border-gray-300 shadow-none">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-toggle-on fs-3 me-2 text-primary"></i> التحكم الفوري في تفعيل وتعطيل الموديولات والأنظمة (ON / OFF)
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="alert alert-light-primary d-flex align-items-center p-4 mb-6 rounded">
                                        <i class="ki-outline ki-information-5 fs-2x text-primary me-3"></i>
                                        <div class="fs-6 text-gray-800">
                                            يمكنك من هنا إيقاف أو تشغيل أي موديول في النظام بالكامل. عند تعطيل أي موديول، يتم حجب خياراته فورياً من تطبيق العميل والسائق والـ APIs.
                                        </div>
                                    </div>

                                    <div class="row g-6">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch form-check-custom form-check-solid p-4 border rounded bg-light d-flex align-items-center justify-content-between">
                                                <div>
                                                    <label class="form-check-label fw-bolder text-gray-800 fs-5 d-block" for="shipping_enabled">
                                                        🚚 نظام شحن البضائع (Shipping System)
                                                    </label>
                                                    <span class="text-muted fs-7">إمكانية إنشاء وإدارة طلبات الشحن ونقل البضائع</span>
                                                </div>
                                                <input class="form-check-input h-30px w-50px" type="checkbox" name="shipping_enabled" value="1" id="shipping_enabled" {{ ($row->shipping_enabled ?? true) ? 'checked' : '' }} />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check form-switch form-check-custom form-check-solid p-4 border rounded bg-light d-flex align-items-center justify-content-between">
                                                <div>
                                                    <label class="form-check-label fw-bolder text-gray-800 fs-5 d-block" for="ride_enabled">
                                                        🚗 نظام رحلات التوصيل (Ride System)
                                                    </label>
                                                    <span class="text-muted fs-7">إمكانية طلب رحلات توصيل الأشخاص داخل المدينة</span>
                                                </div>
                                                <input class="form-check-input h-30px w-50px" type="checkbox" name="ride_enabled" value="1" id="ride_enabled" {{ ($row->ride_enabled ?? true) ? 'checked' : '' }} />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check form-switch form-check-custom form-check-solid p-4 border rounded bg-light d-flex align-items-center justify-content-between">
                                                <div>
                                                    <label class="form-check-label fw-bolder text-gray-800 fs-5 d-block" for="travel_enabled">
                                                        🚌 نظام رحلات السفر والتنقل (Travel System)
                                                    </label>
                                                    <span class="text-muted fs-7">إمكانية طلب رحلات السفر والتنقل بين المدن</span>
                                                </div>
                                                <input class="form-check-input h-30px w-50px" type="checkbox" name="travel_enabled" value="1" id="travel_enabled" {{ ($row->travel_enabled ?? true) ? 'checked' : '' }} />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 5: Cloud & Expenses -->
                        <div class="tab-pane fade" id="tab_cloud_expenses">
                            
                            <div class="card border border-gray-300 shadow-none">
                                <div class="card-header bg-light py-4">
                                    <h4 class="card-title fw-bolder mb-0 text-gray-800">
                                        <i class="ki-outline ki-cloud fs-3 me-2 text-warning"></i> حسابات السحابة والمصروفات وبوابة Paymob
                                    </h4>
                                </div>
                                <div class="card-body p-5">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label fw-bold" for="digitalocean_api_token">ديجيتال أوشن API Token (سحوبات السيرفر)</label>
                                            <input type="text" value="{{ $row->digitalocean_api_token }}" name="digitalocean_api_token" class="form-control form-control-solid" id="digitalocean_api_token" placeholder="أدخل DigitalOcean API Token">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold" for="gcp_service_account_json">Google Cloud Service Account JSON (فواتير فايربيز وجيميني)</label>
                                            <textarea name="gcp_service_account_json" class="form-control form-control-solid" id="gcp_service_account_json" rows="4" placeholder="ألصق محتويات ملف Service Account JSON هنا">{{ $row->gcp_service_account_json }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="gcp_billing_account_id">Google Cloud Billing Account ID</label>
                                            <input type="text" value="{{ $row->gcp_billing_account_id }}" name="gcp_billing_account_id" class="form-control form-control-solid" id="gcp_billing_account_id" placeholder="مثال: 012345-6789AB-CDEF01">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" for="usd_to_egp_exchange_rate">سعر صرف الدولار الافتراضي (مقابل الجنيه المصري)</label>
                                            <input type="number" step="0.01" value="{{ $row->usd_to_egp_exchange_rate }}" name="usd_to_egp_exchange_rate" class="form-control form-control-solid" id="usd_to_egp_exchange_rate" placeholder="سعر الصرف الافتراضي (مثل 50.00)">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" for="paymob_card_commission_percent">عمولة بايموب - الكروت (%)</label>
                                            <input type="number" step="0.01" value="{{ $row->paymob_card_commission_percent }}" name="paymob_card_commission_percent" class="form-control form-control-solid" id="paymob_card_commission_percent">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" for="paymob_card_commission_fixed">عمولة بايموب - الكروت (ثابت ج.م)</label>
                                            <input type="number" step="0.01" value="{{ $row->paymob_card_commission_fixed }}" name="paymob_card_commission_fixed" class="form-control form-control-solid" id="paymob_card_commission_fixed">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" for="paymob_wallet_commission_percent">عمولة بايموب - المحافظ (%)</label>
                                            <input type="number" step="0.01" value="{{ $row->paymob_wallet_commission_percent }}" name="paymob_wallet_commission_percent" class="form-control form-control-solid" id="paymob_wallet_commission_percent">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold" for="paymob_wallet_commission_fixed">عمولة بايموب - المحافظ (ثابت ج.م)</label>
                                            <input type="number" step="0.01" value="{{ $row->paymob_wallet_commission_fixed }}" name="paymob_wallet_commission_fixed" class="form-control form-control-solid" id="paymob_wallet_commission_fixed">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-6">
                        <button type="submit" class="btn btn-primary fw-bold px-8">
                            <i class="ki-outline ki-check fs-4 me-1"></i> {{ __('global.save') ?? 'حفظ الإعدادات' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
