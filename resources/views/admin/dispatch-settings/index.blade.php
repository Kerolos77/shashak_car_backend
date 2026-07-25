@extends('layouts.admin')

@section('title', __('إعدادات التوزيع وحظر السائقين'))

@push('styles')
<style>
    .dispatch-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        transition: all 0.25s ease-in-out;
        background: #ffffff;
    }
    .dispatch-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }
    .dispatch-header {
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }
    .section-title {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #4338ca;
        font-size: 0.85rem;
        padding: 0.35em 0.75em;
        border-radius: 6px;
    }
    .custom-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="ti ti-adjustments-alt text-primary me-2"></i> {{ __('إعدادات التوزيع وحظر السائقين') }}
            </h3>
            <p class="text-muted mb-0">{{ __('تحكم كامل في قواعد حظر الكاش والمسافات المقبولة وتوزيع الرحلات على السائقين') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="ti ti-circle-check fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.dispatch-settings.update') }}" method="POST">
        @csrf

        <!-- Nav Tabs -->
        <ul class="nav nav-pills nav-fill mb-4 bg-light p-2 rounded-3" id="dispatchTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-item nav-link active fw-bold py-3" id="cash-ban-tab" data-bs-toggle="tab" data-bs-target="#cash-ban" type="button" role="tab">
                    <i class="ti ti-ban text-danger me-2"></i> {{ __('قواعد حظر الكاش والمديونية') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-item nav-link fw-bold py-3" id="radius-tab" data-bs-toggle="tab" data-bs-target="#radius" type="button" role="tab">
                    <i class="ti ti-map-pin text-primary me-2"></i> {{ __('نطاق ومسافة التوزيع') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-item nav-link fw-bold py-3" id="cities-tab" data-bs-toggle="tab" data-bs-target="#cities" type="button" role="tab">
                    <i class="ti ti-building text-success me-2"></i> {{ __('تخصيص القواعد حسب المدن') }}
                </button>
            </li>
        </ul>

        <div class="tab-content" id="dispatchTabsContent">

            <!-- TAB 1: CASH BAN RULES -->
            <div class="tab-pane fade show active" id="cash-ban" role="tabpanel">
                <div class="card dispatch-card p-4 mb-4">
                    <div class="dispatch-header d-flex justify-content-between align-items-center">
                        <span class="section-title">
                            <i class="ti ti-shield-lock text-danger"></i> {{ __('إعدادات حظر الكاش التلقائي للسائق') }}
                        </span>
                        <div class="form-check form-switch custom-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="auto_cash_ban_enabled" id="auto_cash_ban_enabled" value="1" {{ old('auto_cash_ban_enabled', $setting->auto_cash_ban_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark ms-2" for="auto_cash_ban_enabled">
                                {{ __('تفعيل الحظر التلقائي للكاش') }}
                            </label>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Max Debt Limit -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('الحد الأقصى لمديونية محفظة السائق (ج.م)') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-currency-pound text-muted"></i></span>
                                <input type="number" step="1" name="max_driver_cash_debt_limit" class="form-control" value="{{ old('max_driver_cash_debt_limit', $setting->max_driver_cash_debt_limit) }}" placeholder="200">
                                <span class="input-group-text bg-light">جنية</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('يتم حظر استقبال رحلات الكاش فور وصول سالب المحفظة لهذا المبلغ.') }}
                            </small>
                        </div>

                        <!-- Min Rating for Cash -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('الحد الأدنى لتقييم السائق المسموح له بالكاش') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-star text-warning"></i></span>
                                <input type="number" step="0.1" min="1" max="5" name="min_driver_rating_for_cash" class="form-control" value="{{ old('min_driver_rating_for_cash', $setting->min_driver_rating_for_cash) }}" placeholder="4.0">
                                <span class="input-group-text bg-light">من 5.0</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('السائقون الحاصلون على تقييم أقل من هذه القيمة سيتم حجب رحلات الكاش عنهم تلقائياً.') }}
                            </small>
                        </div>

                        <!-- Consecutive Cancellations -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('عدد الإلغاءات المتتالية قبل فرض حظر كاش مؤقت') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-alert-triangle text-danger"></i></span>
                                <input type="number" name="max_consecutive_cancellations_before_ban" class="form-control" value="{{ old('max_consecutive_cancellations_before_ban', $setting->max_consecutive_cancellations_before_ban) }}" placeholder="3">
                                <span class="input-group-text bg-light">رحلات</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('عند إلغاء السائق لهذا العدد من الرحلات الكاش متتالية يتم تطبيق عقوبة حظر الكاش المؤقت.') }}
                            </small>
                        </div>

                        <!-- Ban Duration -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('مدة عقوبة حظر الكاش المؤقت (بالدقائق)') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-clock text-info"></i></span>
                                <input type="number" name="cash_restriction_duration_minutes" class="form-control" value="{{ old('cash_restriction_duration_minutes', $setting->cash_restriction_duration_minutes) }}" placeholder="60">
                                <span class="input-group-text bg-light">دقيقة</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('يتم خصم الثواني والدقائق فقط عندما يكون السائق متصلاً بالإنترنت (أونلاين).') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: RADIUS & DISTANCE RULES -->
            <div class="tab-pane fade" id="radius" role="tabpanel">
                <div class="card dispatch-card p-4 mb-4">
                    <div class="dispatch-header">
                        <span class="section-title">
                            <i class="ti ti-map-pin text-primary"></i> {{ __('أقصى مسافة لاستلام الرحلات ونطاق التوزيع') }}
                        </span>
                    </div>

                    <div class="row g-4">
                        <!-- Max Cash Pickup Distance -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('أقصى مسافة لالتقاط رحلات الكاش (KM)') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-run text-success"></i></span>
                                <input type="number" step="0.5" name="max_cash_pickup_distance_km" class="form-control" value="{{ old('max_cash_pickup_distance_km', $setting->max_cash_pickup_distance_km) }}" placeholder="10.0">
                                <span class="input-group-text bg-light">كم</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('أقصى بعد مسموح به بين موقع السائق ومكان الراكب لرحلات الدفع النقدي.') }}
                            </small>
                        </div>

                        <!-- Max Card Pickup Distance -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('أقصى مسافة لالتقاط رحلات الفيزا والمحفظة (KM)') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-credit-card text-primary"></i></span>
                                <input type="number" step="0.5" name="max_card_pickup_distance_km" class="form-control" value="{{ old('max_card_pickup_distance_km', $setting->max_card_pickup_distance_km) }}" placeholder="15.0">
                                <span class="input-group-text bg-light">كم</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('أقصى بعد مسموح به لرحلات الدفع الإلكتروني (يمكن أن يكون أكبر للتسهيل).') }}
                            </small>
                        </div>

                        <!-- Destination Mode Tolerance -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('نسبة الانحراف المقبولة لوجهة السائق (Destination Mode Tolerance)') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ti ti-location-pin text-info"></i></span>
                                <input type="number" step="0.5" name="destination_mode_tolerance_km" class="form-control" value="{{ old('destination_mode_tolerance_km', $setting->destination_mode_tolerance_km) }}" placeholder="5.0">
                                <span class="input-group-text bg-light">كم</span>
                            </div>
                            <small class="text-muted mt-1 d-block">
                                {{ __('عندما يفعل السائق ميزة "وجهتي / رايح بيتي"، يتم استثناء أي رحلة تزيد درجة الانحراف عن وجهته عن هذه المسافة.') }}
                            </small>
                        </div>

                        <!-- Dispatch Priority Strategy -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">
                                {{ __('أولوية توزيع الرحلات على السائقين') }}
                            </label>
                            <select name="dispatch_priority_strategy" class="form-select">
                                <option value="distance" {{ old('dispatch_priority_strategy', $setting->dispatch_priority_strategy) === 'distance' ? 'selected' : '' }}>
                                    🎯 {{ __('أقرب مسافة للسائق (Closest Distance Strategy)') }}
                                </option>
                                <option value="rating" {{ old('dispatch_priority_strategy', $setting->dispatch_priority_strategy) === 'rating' ? 'selected' : '' }}>
                                    ⭐ {{ __('أعلى تقييم للسائق (Highest Rating Priority)') }}
                                </option>
                                <option value="fair_share" {{ old('dispatch_priority_strategy', $setting->dispatch_priority_strategy) === 'fair_share' ? 'selected' : '' }}>
                                    ⚖️ {{ __('التوزيع العادل (Fair Share - الأقل استلاماً اليوم)') }}
                                </option>
                            </select>
                            <small class="text-muted mt-1 d-block">
                                {{ __('الاستراتيجية المعتمده لاختيار السائق الأنسب عند وجود أكثر من سائق متاح في نفس النطاق.') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: CITY OVERRIDES -->
            <div class="tab-pane fade" id="cities" role="tabpanel">
                <div class="card dispatch-card p-4 mb-4">
                    <div class="dispatch-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <span class="section-title mb-1">
                                <i class="ti ti-building text-success"></i> {{ __('تخصيص قواعد النطاق والمسافات للمدن والمحافظات') }}
                            </span>
                            <p class="text-muted small mb-0">
                                {{ __('مرتبة أبجدياً. جميع المدن تعمل بالقيم العامة الافتراضية ما لم تقم بتحديد قيم مخصصة لمدينة معينة.') }}
                            </p>
                        </div>
                        <div style="min-width: 280px;">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-muted"></i></span>
                                <input type="text" id="citySearchInput" class="form-control border-start-0" placeholder="{{ __('ابحث باسم المدينة...') }}" onkeyup="filterCitiesTable()">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-hover align-middle border rounded-3" id="citiesTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">{{ __('المدينة (أبجدي)') }}</th>
                                    <th style="width: 25%;">{{ __('مسافة الكاش (KM)') }}</th>
                                    <th style="width: 25%;">{{ __('مسافة الفيزا (KM)') }}</th>
                                    <th style="width: 25%;">{{ __('الحالة') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    @php
                                        $cityOverrides = $setting->city_override_settings[$city->id] ?? [];
                                        $cashDist = $cityOverrides['cash_dist'] ?? '';
                                        $cardDist = $cityOverrides['card_dist'] ?? '';
                                    @endphp
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            <i class="ti ti-map-pin text-danger me-1"></i> {{ $city->name ?? $city->title ?? ('مدينة #' . $city->id) }}
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" name="city_override_settings[{{ $city->id }}][cash_dist]" value="{{ $cashDist }}" class="form-control form-control-sm" placeholder="{{ $setting->max_cash_pickup_distance_km }} (الافتراضي)">
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" name="city_override_settings[{{ $city->id }}][card_dist]" value="{{ $cardDist }}" class="form-control form-control-sm" placeholder="{{ $setting->max_card_pickup_distance_km }} (الافتراضي)">
                                        </td>
                                        <td>
                                            @if($cashDist || $cardDist)
                                                <span class="badge bg-success">{{ __('مخصص للمدينة') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('القيم العامة الافتراضية') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            {{ __('لا توجد مدن معرفة حالياً في النظام.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-3 fw-bold shadow-sm">
                <i class="ti ti-device-floppy me-2"></i> {{ __('حفظ وتطبيق إعدادات التوزيع') }}
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function filterCitiesTable() {
        let input = document.getElementById('citySearchInput');
        let filter = input.value.toLowerCase().trim();
        let table = document.getElementById('citiesTable');
        if (!table) return;
        let rows = table.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) {
            let cityNameCell = rows[i].getElementsByTagName('td')[0];
            if (cityNameCell) {
                let textValue = cityNameCell.textContent || cityNameCell.innerText;
                if (textValue.toLowerCase().indexOf(filter) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }
    }
</script>
@endpush
