@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.drivers') ?? 'السائقون' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.view') ?? 'عرض التفاصيل' }}</li>
@endsection

@section('content')
@section('title', $pageTitle)

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-5" role="alert">
        <i class="ki-outline ki-check-circle fs-3 me-2 text-success"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-7">
    <!-- Left Column - Profile Summary & Actions -->
    <div class="col-xl-4">
        <!-- Main Profile Summary -->
        <div class="card mb-7 shadow-sm">
            <div class="card-body p-6 text-center">
                <!-- User Avatar -->
                <div class="symbol symbol-120px symbol-circle mb-5 border border-primary border-opacity-20 p-2 bg-white bg-opacity-20 position-relative">
                    <img src="{{ asset($row->user->photo ?? 'assets/media/avatars/blank.png') }}" alt="{{ $row->user->full_name }}" style="object-fit: cover;">
                    @if($row->user->is_vip)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark fs-8 border border-white fw-bolder px-2 shadow">
                            ⭐ VIP
                        </span>
                    @endif
                </div>
                
                <h3 class="text-gray-900 fw-bold fs-3 mb-1">
                    {{ $row->user->full_name }}
                    @if($row->user->is_vip)
                        <span class="badge badge-light-warning text-dark fw-bold ms-1" title="سائق مميز (VIP)">⭐ VIP</span>
                    @endif
                </h3>
                <span class="badge badge-light-primary fw-semibold fs-7 mb-4">{{ $row->user->email }}</span>
                
                <!-- Status Badges -->
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                    @if($row->status == 'active')
                        <span class="capsule-badge badge-soft-success fs-7 py-1 px-3">{{ __('app.active') }}</span>
                    @elseif($row->status == 'pending')
                        <span class="capsule-badge badge-soft-warning fs-7 py-1 px-3">{{ __('app.pending') }}</span>
                    @elseif($row->status == 'blocked')
                        <span class="capsule-badge badge-soft-danger fs-7 py-1 px-3">{{ __('app.blocked') }}</span>
                    @else
                        <span class="capsule-badge badge-soft-primary fs-7 py-1 px-3">{{ $row->status }}</span>
                    @endif

                    <!-- Cash Restriction Status -->
                    @if(($row->user->cash_restriction_seconds_remaining ?? 0) > 0)
                        <span class="badge bg-danger text-white fs-7 py-2 px-3" title="محظور من الكاش مؤقتاً">
                            <i class="ki-outline ki-lock text-white me-1"></i> محظور كاش ({{ ceil($row->user->cash_restriction_seconds_remaining / 60) }} دقيقة متبقية)
                        </span>
                    @else
                        <span class="badge bg-light-success text-success fs-7 py-2 px-3">
                            <i class="ki-outline ki-check text-success me-1"></i> الكاش متاح
                        </span>
                    @endif
                </div>

                <div class="separator separator-dashed my-4"></div>

                <!-- Admin Control Actions -->
                <div class="d-flex flex-column gap-2 text-start">
                    <span class="text-muted fw-bold fs-8 uppercase tracking-wider mb-1">إجراءات لوحة التحكم السريعة</span>
                    
                    <div class="d-flex gap-2">
                        <!-- Block / Unblock General -->
                        @if($row->status == 'blocked')
                            <form action="{{ route('admin.drivers.active', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success w-100 py-2">
                                    <i class="ki-outline ki-check-circle fs-5 me-1"></i> فك الحظر العام
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.drivers.block', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-2" onclick="return confirm('هل أنت تأكد من حظر السائق؟')">
                                    <i class="ki-outline ki-ban fs-5 me-1"></i> حظر السائق
                                </button>
                            </form>
                        @endif

                        <!-- Reset Cash Ban -->
                        @if(($row->user->cash_restriction_seconds_remaining ?? 0) > 0)
                            <form action="{{ route('admin.drivers.reset-cash-ban', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning text-dark w-100 py-2" title="تصفير وفك حظر الكاش فوراً">
                                    <i class="ki-outline ki-key fs-5 me-1"></i> فك حظر الكاش
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="d-flex gap-2 mt-1">
                        <!-- Toggle VIP -->
                        <form action="{{ route('admin.drivers.toggle-vip', $row->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $row->user->is_vip ? 'btn-light-warning' : 'btn-outline-warning' }} w-100 py-2">
                                <i class="ki-outline ki-star fs-5 me-1"></i> {{ $row->user->is_vip ? 'إلغاء VIP' : 'تمبيز كـ VIP ⭐' }}
                            </button>
                        </form>

                        <!-- Add Wallet Money Modal Button -->
                        <button type="button" class="btn btn-sm btn-primary w-100 py-2" data-bs-toggle="modal" data-bs-target="#addWalletModal">
                            <i class="ki-outline ki-wallet fs-5 me-1"></i> إضافة رصيد
                        </button>
                    </div>

                    <div class="d-flex gap-2 mt-1">
                        <!-- Gift Package Modal Button -->
                        <button type="button" class="btn btn-sm btn-info text-white w-100 py-2" data-bs-toggle="modal" data-bs-target="#giftPackageModal">
                            <i class="ki-outline ki-gift fs-5 me-1"></i> إهداء باقة
                        </button>
                        
                        <a href="{{ route('admin.drivers.edit', $row->id) }}" class="btn btn-sm btn-light w-100 py-2">
                            <i class="ki-outline ki-pencil fs-5 me-1"></i> {{ trans('global.edit') }}
                        </a>
                    </div>
                </div>

                <div class="separator separator-dashed my-4"></div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.drivers.export-pdf', $row->id) }}" class="btn btn-sm btn-danger py-2" target="_blank">
                        <i class="ki-outline ki-document fs-5 me-1"></i> تصدير ملف أمني رسمي (PDF)
                    </a>
                </div>
            </div>
        </div>

        <!-- Wallet Highlights Card -->
        <div class="card mb-7 shadow-sm">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">{{ __('admin.financial_statistics') }}</h3>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center mb-4">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-success">
                            <i class="ki-duotone ki-wallet fs-2 text-success"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.admin.fields.wallet_amount') }}</span>
                        <span class="text-gray-900 fw-bold fs-4">{{ number_format($row->user->wallet_amount ?? 0, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45px me-4">
                        <span class="symbol-label badge-soft-warning">
                            <i class="ki-duotone ki-hourglass fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <span class="text-gray-500 fw-semibold fs-7">{{ trans('cruds.admin.fields.pending_wallet') }}</span>
                        <span class="text-gray-900 fw-bold fs-4">{{ number_format($row->user->pending_wallet ?? 0, 2) }} {{ __('admin.egp') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Information, Stats, Reviews & Audit -->
    <div class="col-xl-8">
        <!-- Order Stats Row -->
        <div class="row g-4 mb-7">
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-primary border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">إجمالي الرحلات</span>
                    <span class="fs-2x fw-bold text-primary">{{ $orderStats['total'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-success border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">الرحلات المكتملة</span>
                    <span class="fs-2x fw-bold text-success">{{ $orderStats['completed'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-danger border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">الرحلات الملغاة</span>
                    <span class="fs-2x fw-bold text-danger">{{ $orderStats['canceled'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-info border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">إجمالي الأرباح</span>
                    <span class="fs-2x fw-bold text-info">{{ number_format($orderStats['total_earnings'], 0) }}ج.م</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6 fw-bold">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab_driver_info">
                    <i class="ki-outline ki-user fs-4 me-1"></i> معلومات السائق والأوراق
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_reviews">
                    <i class="ki-outline ki-star fs-4 me-1"></i> التقييمات والمراجعات ({{ $reviews->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_packages">
                    <i class="ki-outline ki-box fs-4 me-1"></i> الباقات والاشتراكات ({{ $activePackages->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_audit">
                    <i class="ki-outline ki-shield-check fs-4 me-1"></i> سجل إجراءات الإدارة ({{ $auditLogs->count() }})
                </a>
            </li>
        </ul>

        <div class="tab-content" id="driverTabContent">
            <!-- TAB 1: Driver Information & Documents -->
            <div class="tab-pane fade show active" id="tab_driver_info" role="tabpanel">
                <div class="card mb-7 shadow-sm">
                    <div class="card-header pt-6 pb-2 border-0">
                        <h3 class="card-label fw-bold text-gray-900 fs-4">{{ trans('cruds.driver.fields.driver_information') }}</h3>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-4">
                                <tbody>
                                    <tr>
                                        <th class="fw-bold text-gray-500 w-200px">ID</th>
                                        <td class="fw-bold text-gray-900">#{{ $row->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.full_name') }}</th>
                                        <td class="fw-bold text-gray-900">{{ $row->user->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.email') }}</th>
                                        <td class="fw-bold text-gray-900">{{ $row->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold text-gray-500">{{ trans('cruds.admin.fields.phone_number') }}</th>
                                        <td class="fw-bold text-gray-900">{{ $row->user->phone_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="fw-bold text-gray-500">متوسط التقييم العام</th>
                                        <td class="fw-bold text-gray-900">
                                            ⭐ {{ number_format($row->user->rating ?? 5.0, 2) }} / 5.0
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Reviews Management -->
            <div class="tab-pane fade" id="tab_driver_reviews" role="tabpanel">
                <div class="card shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-gray-900 mb-0">تقييمات ومراجعات الركاب السابقة</h4>
                        <span class="badge bg-light-primary text-primary fw-bold">العدد: {{ $reviews->count() }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>الراكب</th>
                                    <th>التقييم</th>
                                    <th>التعليق</th>
                                    <th>التاريخ</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $rev)
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            {{ $rev->fromUser->full_name ?? $rev->fromUser->name ?? 'راكب #' . $rev->from_user_id }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light-warning text-dark fw-bold">⭐ {{ $rev->rating }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $rev->comment ?? 'لا يوجد تعليق' }}
                                        </td>
                                        <td class="text-muted small">
                                            {{ $rev->created_at ? $rev->created_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('هل أنت ممتأكد من حذف هذا التقييم؟ سيتم إعادة حساب متوسط تقييم السائق تلقائياً.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف التقييم الكيدي">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد تقييمات مكتوبة لهذا السائق حتى الآن.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: Packages & Purchases -->
            <div class="tab-pane fade" id="tab_driver_packages" role="tabpanel">
                <div class="card shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-gray-900 mb-0">الباقات والاشتراكات الحالية</h4>
                        <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#giftPackageModal">
                            <i class="ki-outline ki-gift me-1"></i> إهداء باقة جديدة
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم الباقة</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activePackages as $purch)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $purch->package->name ?? 'باقة مخصصة' }}</td>
                                        <td>{{ $purch->expires_at ? $purch->expires_at->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            @if($purch->expires_at && $purch->expires_at->isFuture())
                                                <span class="badge bg-success">نشطة</span>
                                            @else
                                                <span class="badge bg-secondary">منتهية</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">السائق غير مشترك في أي باقات حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: Admin Audit Logs -->
            <div class="tab-pane fade" id="tab_driver_audit" role="tabpanel">
                <div class="card shadow-sm p-4">
                    <div class="mb-4">
                        <h4 class="fw-bold text-gray-900 mb-1">سجل إجراءات الإدارة على الحساب</h4>
                        <p class="text-muted small mb-0">سجل شفاف بجميع التعديلات والمعاملات التي قام بها الآدمين لضمان الدقة والرقابة.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>المشرف / Admin</th>
                                    <th>الإجراء</th>
                                    <th>التفاصيل والملاحظات</th>
                                    <th>التاريخ والوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            <i class="ki-outline ki-user text-primary me-1"></i>
                                            {{ $log->admin->name ?? $log->admin->email ?? 'مشرف النظام' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary fw-bold">{{ $log->action }}</span>
                                        </td>
                                        <td class="text-dark small">{{ $log->notes }}</td>
                                        <td class="text-muted small">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">لا توجد سجلات سابقة للمشرفين على هذا الحساب.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal: Add Wallet Balance -->
<div class="modal fade" id="addWalletModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.drivers.add-wallet', $row->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">إضافة / خصم رصيد في محفظة السائق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">المبلغ (جنية مصري)</label>
                    <input type="number" step="0.5" name="amount" class="form-control" placeholder="أدخل المبلغ (استخدم سالب - لخصم رصيد)" required>
                    <small class="text-muted">أدخل 100 للإضافة أو -50 للخصم من المحفظة.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">سبب/ملاحظة الإضافة</label>
                    <input type="text" name="notes" class="form-control" placeholder="مثال: مكافأة تميز / تسوية شحن">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary fw-bold">حفظ وتعديل الرصيد</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Gift Package -->
<div class="modal fade" id="giftPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.drivers.gift-package', $row->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">إهداء باقة مجانية للسائق</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">اختر الباقة المراد إهدائها</label>
                    <select name="package_id" class="form-select" required>
                        <option value="">-- اختر باقة من الباقات المتاحة --</option>
                        @foreach($availablePackages as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->duration_days ?? 30 }} يوم)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-info text-white fw-bold">تفعيل وتطبيق الباقة الهدية</button>
            </div>
        </form>
    </div>
</div>

@endsection
