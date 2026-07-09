@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> ????????? </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">?????</li>
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
                    title: '{{ __("global.add_value") ?? "Add Value" }}',
                    text: '{{ __("global.choose_value_type") ?? "Choose the type of value you want to add" }}',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __("cruds.setting.fields.increase") ?? "Static Value" }}',
                    cancelButtonText: '{{ __("cruds.setting.fields.percentage_increase") ?? "Percentage Value" }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Add static value
                        addStaticValue();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // Add percentage value
                        addPercentageValue();
                    }
                });
            });

            function addStaticValue() {
                var count = $(document).find('input[name="increase[]"]').length;
                // Find the static values section
                var staticSection = $('#static-values-section');
                staticSection.append(`<div><div class="row"><div class="mb-3 col-10 mb-0"><label class="form-label" for="form-repeater-${count}-1">{{ __('cruds.setting.fields.increase') }}</label><input type="number" value="" step="0.5" name="increase[]" id="form-repeater-${count}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.increase') }}" /></div><div class="mb-3 col-2 d-flex align-items-center mb-0"><button class="btn btn-label-danger mt-4 data-repeater-delete" type="button"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div></div></div>`);
            }

            function addPercentageValue() {
                var count = $(document).find('input[name="percentage_increase[]"]').length;
                // Find the percentage section
                var percentageSection = $('#percentage-values-section');
                percentageSection.append(`<div><div class="row"><div class="mb-3 col-10 mb-0"><label class="form-label" for="form-repeater-percentage-${count}-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</label><div class="input-group"><input type="number" value="" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-${count}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}" /><span class="input-group-text">%</span></div></div><div class="mb-3 col-2 d-flex align-items-center mb-0"><button class="btn btn-label-danger mt-4 data-repeater-delete-percentage" type="button"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div></div></div>`);
            }

            $(document).on('click', '.data-repeater-delete', function() {
                $(this).parent().parent().parent().remove();
            });

            $(document).on('click', '.data-repeater-delete-percentage', function() {
                $(this).parent().parent().parent().remove();
            });

            // Handle active type change
            $('input[name="active_type"]').on('change', function() {
                var selectedType = $(this).val();
                console.log('Selected type:', selectedType);
                
                if (selectedType === 'increase') {
                    // Show static values section, hide percentage section
                    $('#static-values-section').show();
                    $('#percentage-values-section').hide();
                } else if (selectedType === 'percentage_increase') {
                    // Hide static values section, show percentage section
                    $('#static-values-section').hide();
                    $('#percentage-values-section').show();
                }
            });

            // Initialize on page load
            var activeType = $('input[name="active_type"]:checked').val();
            console.log('Initial active type:', activeType);
            
            if (activeType === 'increase') {
                $('#static-values-section').show();
                $('#percentage-values-section').hide();
            } else if (activeType === 'percentage_increase') {
                $('#static-values-section').hide();
                $('#percentage-values-section').show();
            } else {
                // Default to showing both if no selection
                $('#static-values-section').show();
                $('#percentage-values-section').show();
            }
            
        });
    </script>
@endpush
<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <form action="{{ route('admin.settings.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-facebook">{{ __('cruds.setting.fields.facebook') }}</label>
                                <input type="url" value="{{ $row->facebook }}" name="facebook"
                                    class="form-control" id="basic-default-facebook"
                                    placeholder="{{ __('cruds.setting.fields.facebook') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-youtube">{{ __('cruds.setting.fields.youtube') }}</label>
                                <input type="url" value="{{ $row->full_name }}" name="youtube"
                                    class="form-control" id="basic-default-youtube"
                                    placeholder="{{ __('cruds.setting.fields.youtube') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-linkedin">{{ __('cruds.setting.fields.linkedin') }}</label>
                                <input type="url" value="{{ $row->linkedin }}" name="linkedin"
                                    class="form-control" id="basic-default-linkedin"
                                    placeholder="{{ __('cruds.setting.fields.linkedin') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-twitter">{{ __('cruds.setting.fields.twitter') }}</label>
                                <input type="url" value="{{ $row->full_name }}" name="twitter"
                                    class="form-control" id="basic-default-twitter"
                                    placeholder="{{ __('cruds.setting.fields.twitter') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-tiktok">{{ __('cruds.setting.fields.tiktok') }}</label>
                                <input type="url" value="{{ $row->tiktok }}" name="tiktok"
                                    class="form-control" id="basic-default-tiktok"
                                    placeholder="{{ __('cruds.setting.fields.tiktok') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-link_1">{{ __('cruds.setting.fields.link_1') }}</label>
                                <input type="url" value="{{ $row->link_1 }}" name="link_1"
                                    class="form-control" id="basic-default-link_1"
                                    placeholder="{{ __('cruds.setting.fields.link_1') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-link_2">{{ __('cruds.setting.fields.link_2') }}</label>
                                <input type="url" value="{{ $row->link_2 }}" name="link_2"
                                    class="form-control" id="basic-default-link_2"
                                    placeholder="{{ __('cruds.setting.fields.link_2') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-link_3">{{ __('cruds.setting.fields.link_3') }}</label>
                                <input type="url" value="{{ $row->link_3 }}" name="link_3"
                                    class="form-control" id="basic-default-link_3"
                                    placeholder="{{ __('cruds.setting.fields.link_3') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-email_1">{{ __('cruds.setting.fields.email_1') }}</label>
                                <input type="email" value="{{ $row->email_1 }}" name="email_1"
                                    class="form-control" id="basic-default-email_1"
                                    placeholder="{{ __('cruds.setting.fields.email_1') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-email_2">{{ __('cruds.setting.fields.email_2') }}</label>
                                <input type="email" value="{{ $row->email_2 }}" name="email_2"
                                    class="form-control" id="basic-default-email_2"
                                    placeholder="{{ __('cruds.setting.fields.email_2') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-email_3">{{ __('cruds.setting.fields.email_3') }}</label>
                                <input type="email" value="{{ $row->email_3 }}" name="email_3"
                                    class="form-control" id="basic-default-email_3"
                                    placeholder="{{ __('cruds.setting.fields.email_3') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                <h5 class="mb-3">{{ __('cruds.setting.fields.active_type') }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="active_type" id="active_increase" value="increase" {{ $row->active_type == 'increase' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active_increase">
                                                {{ __('cruds.setting.fields.increase') }} (قيمة ثابتة)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="active_type" id="active_percentage" value="percentage_increase" {{ $row->active_type == 'percentage_increase' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="active_percentage">
                                                {{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }} (نسبة مئوية)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Static Values Section -->
                            <div class="form-repeater" id="static-values-section">
                                <div class="form-repeater-item">
                                    <h5 class="mb-3">{{ __('cruds.setting.fields.increase') }}</h5>
                                    @if ($row->increase != null)
                                    @foreach ($row->increase as $item)
                                        @php
                                        $c = 0;
                                        @endphp
                                    <div>
                                        <div class="row">
                                            <div class="mb-3 col-10 mb-0">
                                                    <label class="form-label" for="form-repeater-{{ $c }}-1">{{ __('cruds.setting.fields.increase') }}</label>
                                                    <input type="number" value="{{ $item }}" step="0.5" name="increase[]" id="form-repeater-{{ $c }}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.increase') }}" />
                                            </div>
                                            <div class="mb-3 col-2 d-flex align-items-center mb-0">
                                                <button class="btn btn-label-danger mt-4 data-repeater-delete" type="button"> 
                                                  <i class="ti ti-x ti-xs me-1"></i>
                                                  <span class="align-middle">Delete</span>
                                                </button>
                                              </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div>
                                        <div class="row">
                                          <div class="mb-3 col-10 mb-0">
                                            <label class="form-label" for="form-repeater-0-1">{{ __('cruds.setting.fields.increase') }}</label>
                                            <input type="number" value="5" step="0.5" name="increase[]" id="form-repeater-0-1" class="form-control" placeholder="{{ __('cruds.setting.fields.increase') }}" />
                                          </div>
                                          <div class="mb-3 col-2 d-flex align-items-center mb-0">
                                            <button class="btn btn-label-danger mt-4 data-repeater-delete"  type="button"> 
                                              <i class="ti ti-x ti-xs me-1"></i>
                                              <span class="align-middle">Delete</span>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Percentage Values Section -->
                            <div class="form-repeater" id="percentage-values-section">
                                <div class="form-repeater-item">
                                    <h5 class="mb-3">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</h5>
                                    @if ($row->percentage_increase != null && count($row->percentage_increase) > 0)
                                    @foreach ($row->percentage_increase as $index => $item)
                                    <div>
                                        <div class="row">
                                            <div class="mb-3 col-10 mb-0">
                                                    <label class="form-label" for="form-repeater-percentage-{{ $index }}-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</label>
                                                    <div class="input-group">
                                                        <input type="number" value="{{ $item }}" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-{{ $index }}-1" class="form-control" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}" />
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                            </div>
                                            <div class="mb-3 col-2 d-flex align-items-center mb-0">
                                                <button class="btn btn-label-danger mt-4 data-repeater-delete-percentage" type="button"> 
                                                  <i class="ti ti-x ti-xs me-1"></i>
                                                  <span class="align-middle">Delete</span>
                                                </button>
                                              </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div>
                                        <div class="row">
                                          <div class="mb-3 col-10 mb-0">
                                            <label class="form-label" for="form-repeater-percentage-0-1">{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}</label>
                                            <div class="input-group">
                                                <input type="number" value="5" step="0.1" name="percentage_increase[]" id="form-repeater-percentage-0-1" class="form-control" placeholder="{{ __('cruds.setting.fields.percentage_increase') ?? 'Percentage Increase' }}" />
                                                <span class="input-group-text">%</span>
                                            </div>
                                          </div>
                                          <div class="mb-3 col-2 d-flex align-items-center mb-0">
                                            <button class="btn btn-label-danger mt-4 data-repeater-delete-percentage"  type="button"> 
                                              <i class="ti ti-x ti-xs me-1"></i>
                                              <span class="align-middle">Delete</span>
                                            </button>
                                          </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                                
                                <!-- Add Button at the bottom -->
                                <div class="mb-0 mt-4">
                                    <button class="btn btn-primary addValue" type="button">
                                      <i class="ti ti-plus me-1"></i>
                                      <span class="align-middle">Add</span>
                                    </button>
                                  </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-min_withdraw">{{ __('cruds.setting.fields.min_withdraw') }}</label>
                                <input type="number" step="0.5" value="{{ $row->min_withdraw }}" name="min_withdraw"
                                    class="form-control" id="basic-default-min_withdraw"
                                    placeholder="{{ __('cruds.setting.fields.min_withdraw') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-min_deposit">{{ __('cruds.setting.fields.min_deposit') }}</label>
                                <input type="number" step="0.5" value="{{ $row->min_deposit }}" name="min_deposit"
                                    class="form-control" id="basic-default-min_deposit"
                                    placeholder="{{ __('cruds.setting.fields.min_deposit') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-request_price">{{ __('cruds.setting.fields.request_price') }}</label>
                                <input type="number" step="0.5" value="{{ $row->request_price }}" name="request_price"
                                    class="form-control" id="basic-default-request_price"
                                    placeholder="{{ __('cruds.setting.fields.request_price') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-referral_bonus">{{ __('cruds.setting.fields.referral_bonus') }}</label>
                                <input type="number" step="0.5" value="{{ $row->referral_bonus }}" name="referral_bonus"
                                    class="form-control" id="basic-default-referral_bonus"
                                    placeholder="{{ __('cruds.setting.fields.referral_bonus') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-commission_percentage">{{ __('cruds.setting.fields.commission_percentage') }} (%)</label>
                                <input type="number" step="0.5" value="{{ $row->commission_percentage }}"
                                    name="commission_percentage" class="form-control"
                                    id="basic-default-commission_percentage"
                                    placeholder="{{ __('cruds.setting.fields.commission_percentage') }}">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                    for="basic-default-min_driver_wallet_for_shipping">الحد الأدنى لمحفظة السائق لرحلات الشحن (ج.م)</label>
                                <input type="number" step="0.5" value="{{ $row->min_driver_wallet_for_shipping }}"
                                    name="min_driver_wallet_for_shipping" class="form-control"
                                    id="basic-default-min_driver_wallet_for_shipping"
                                    placeholder="الحد الأدنى لمحفظة السائق لرحلات الشحن">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-gemini_api_key">مفتاح API الخاص بالتحقق بالذكاء الاصطناعي (Gemini API Key)</label>
                                <input type="text" value="{{ $row->gemini_api_key }}" name="gemini_api_key" class="form-control"
                                    id="basic-default-gemini_api_key"
                                    placeholder="أدخل مفتاح Gemini API هنا">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="basic-default-gemini_model">اسم نموذج الذكاء الاصطناعي المستخدم (Gemini Model Name)</label>
                                <select name="gemini_model" class="form-select" id="basic-default-gemini_model">
                                    <option value="gemini-3.5-flash" {{ ($row->gemini_model ?? 'gemini-3.5-flash') == 'gemini-3.5-flash' ? 'selected' : '' }}>Gemini 3.5 Flash (الأحدث والأنسب للتحقق السريع)</option>
                                    <option value="gemini-3.5-pro" {{ ($row->gemini_model ?? '') == 'gemini-3.5-pro' ? 'selected' : '' }}>Gemini 3.5 Pro (الأقوى للأعمال المعقدة والذكاء العالي)</option>
                                    <option value="gemini-3.1-flash" {{ ($row->gemini_model ?? '') == 'gemini-3.1-flash' ? 'selected' : '' }}>Gemini 3.1 Flash (مستقر وسريع)</option>
                                    <option value="gemini-3.1-flash-lite" {{ ($row->gemini_model ?? '') == 'gemini-3.1-flash-lite' ? 'selected' : '' }}>Gemini 3.1 Flash-Lite (خفيف وسريع جداً للضغط العالي)</option>
                                    <option value="gemini-3.1-pro" {{ ($row->gemini_model ?? '') == 'gemini-3.1-pro' ? 'selected' : '' }}>Gemini 3.1 Pro (ذكي ومناسب للعمليات الطويلة والمنطق المعقد)</option>
                                </select>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h4 class="mb-3">إعدادات حساب وإدارة المصروفات</h4>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="digitalocean_api_token">ديجيتال أوشن API Token (سحوبات السيرفر)</label>
                                <input type="text" value="{{ $row->digitalocean_api_token }}" name="digitalocean_api_token" class="form-control"
                                    id="digitalocean_api_token" placeholder="أدخل DigitalOcean API Token">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label" for="gcp_service_account_json">Google Cloud Service Account JSON (فواتير فايربيز وجيميني)</label>
                                <textarea name="gcp_service_account_json" class="form-control" id="gcp_service_account_json" rows="4" 
                                    placeholder="ألصق محتويات ملف Service Account JSON هنا">{{ $row->gcp_service_account_json }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="gcp_billing_account_id">Google Cloud Billing Account ID</label>
                                <input type="text" value="{{ $row->gcp_billing_account_id }}" name="gcp_billing_account_id" class="form-control"
                                    id="gcp_billing_account_id" placeholder="مثال: 012345-6789AB-CDEF01">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="usd_to_egp_exchange_rate">سعر صرف الدولار الافتراضي (مقابل الجنيه المصري)</label>
                                <input type="number" step="0.01" value="{{ $row->usd_to_egp_exchange_rate }}" name="usd_to_egp_exchange_rate" class="form-control"
                                    id="usd_to_egp_exchange_rate" placeholder="سعر الصرف الافتراضي (مثل 50.00)">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label" for="paymob_card_commission_percent">عمولة بايموب - الكروت (%)</label>
                                <input type="number" step="0.01" value="{{ $row->paymob_card_commission_percent }}" name="paymob_card_commission_percent" class="form-control" id="paymob_card_commission_percent">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label" for="paymob_card_commission_fixed">عمولة بايموب - الكروت (مبلغ ثابت ج.م)</label>
                                <input type="number" step="0.01" value="{{ $row->paymob_card_commission_fixed }}" name="paymob_card_commission_fixed" class="form-control" id="paymob_card_commission_fixed">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label" for="paymob_wallet_commission_percent">عمولة بايموب - المحافظ (%)</label>
                                <input type="number" step="0.01" value="{{ $row->paymob_wallet_commission_percent }}" name="paymob_wallet_commission_percent" class="form-control" id="paymob_wallet_commission_percent">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label" for="paymob_wallet_commission_fixed">عمولة بايموب - المحافظ (مبلغ ثابت ج.م)</label>
                                <input type="number" step="0.01" value="{{ $row->paymob_wallet_commission_fixed }}" name="paymob_wallet_commission_fixed" class="form-control" id="paymob_wallet_commission_fixed">
                            </div>
                        </div>
                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('global.save') }}</button>

                    </div>

                </div>
            </form>
        </div>
    </div>
</div>




@endsection
