@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.driver-applications.index') }}" class="text-muted text-hover-primary">طلبات الانضمام</a>
    </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">مراجعة ومقارنة مستندات السائق</li>
@endsection

@section('content')
@section('title', $pageTitle)

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-5" role="alert">
        <i class="ki-outline ki-check-circle fs-3 me-2 text-success"></i>
        <div class="fw-bold">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Top Decision & Status Header Banner -->
<div class="card mb-7 shadow-sm border-0">
    <div class="card-body p-6">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <div class="d-flex align-items-center">
                @if($driver->status == 'pending')
                    <div class="symbol symbol-50px me-4">
                        <span class="symbol-label bg-light-warning">
                            <i class="ki-outline ki-hourglass fs-2x text-warning"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="fw-bold text-gray-900 mb-1">حالة طلب التقديم: <span class="badge badge-light-warning fs-6 px-3 py-2">قيد المراجعة والتدقيق</span></h4>
                        <p class="text-muted fs-7 mb-0">قم بمقارنة مستندات وصور السائق في هذه الصفحة بالبيانات المكتوبة قبل إتخاذ قرار الاعتماد أو الرفض.</p>
                    </div>
                @elseif($driver->status == 'rejected')
                    <div class="symbol symbol-50px me-4">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-outline ki-cross-circle fs-2x text-danger"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="fw-bold text-gray-900 mb-1">حالة طلب التقديم: <span class="badge badge-light-danger fs-6 px-3 py-2">طلب مرفوض</span></h4>
                        <div class="mt-2 p-3 bg-light-danger rounded border border-danger border-dashed">
                            <strong class="text-danger fs-7 d-block mb-1"><i class="ki-outline ki-information-5 text-danger me-1"></i> سبب الرفض الموجه للسائق حالياً:</strong>
                            <span class="text-gray-800 fs-7 fw-semibold">{{ $rejectionReason ?? $driver->latest_rejection_reason ?? 'لم يتم تحديد سبب الرفض' }}</span>
                        </div>
                    </div>
                @elseif($driver->status == 'active')
                    <div class="symbol symbol-50px me-4">
                        <span class="symbol-label bg-light-success">
                            <i class="ki-outline ki-check-circle fs-2x text-success"></i>
                        </span>
                    </div>
                    <div>
                        <h4 class="fw-bold text-gray-900 mb-1">حالة طلب التقديم: <span class="badge badge-light-success fs-6 px-3 py-2">مقبول ومفعّل</span></h4>
                        <p class="text-muted fs-7 mb-0">تم اعتماد أوراق ورخص السائق بنجاح وهو متاح للعمل في التطبيق.</p>
                    </div>
                @else
                    <div>
                        <h4 class="fw-bold text-gray-900 mb-1">حالة الحساب: <span class="badge badge-light-primary fs-6 px-3 py-2">{{ $driver->status }}</span></h4>
                    </div>
                @endif
            </div>

            <!-- Decision Action Buttons -->
            <div class="d-flex flex-wrap gap-2 align-items-center">
                @if($driver->status == 'pending' || $driver->status == 'rejected')
                    <form action="{{ route('admin.driver-applications.approve', $driver->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" onclick="return confirm('هل أنت متأكد من صحة المستندات وقبول تفعيل حساب السائق؟')" class="btn btn-success fw-bold px-4 py-3">
                            <i class="ki-outline ki-check-circle fs-4 me-1"></i> قبول وتفعيل الحساب
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger fw-bold px-4 py-3" data-bs-toggle="modal" data-bs-target="#rejectApplicationModal">
                        <i class="ki-outline ki-cross-circle fs-4 me-1"></i> {{ $driver->status == 'rejected' ? 'تعديل سبب الرفض' : 'رفض الطلب مع إدخال السبب' }}
                    </button>
                @elseif($driver->status == 'active')
                    <form action="{{ route('admin.drivers.block', $driver->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" onclick="return confirm('هل أنت متأكد من حظر السائق؟')" class="btn btn-outline-danger fw-bold px-4 py-3">
                            <i class="ki-outline ki-ban fs-4 me-1"></i> حظر السائق
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-7">
    <!-- Left Column: Data Verification Form -->
    <div class="col-xl-4">
        <!-- Personal Summary Card -->
        <div class="card mb-7 shadow-sm">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">البيانات الشخصية</h3>
            </div>
            <div class="card-body pt-0 text-center">
                <div class="symbol symbol-100px symbol-circle mb-4 border border-primary p-1 bg-white">
                    <img src="{{ $documents['personal_photo'] }}" alt="{{ $user->full_name }}" style="object-fit: cover;">
                </div>
                <h3 class="text-gray-900 fw-bold fs-4 mb-1">{{ $user->full_name }}</h3>
                <span class="badge badge-light-primary fw-semibold fs-7 mb-4">{{ $user->email }}</span>

                <div class="table-responsive text-start mt-3">
                    <table class="table align-middle table-row-dashed fs-7 gy-3">
                        <tbody>
                            <tr>
                                <th class="fw-bold text-gray-500 w-120px">رقم الهاتف</th>
                                <td class="fw-bold text-gray-900">{{ $user->phone_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">الرقم القومي</th>
                                <td class="fw-bold text-primary fs-6">{{ $driver->id_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">فئة الخدمة</th>
                                <td class="fw-bold text-gray-900">{{ $driver->service->title ?? 'خدمة عامة' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">المدينة / الدولة</th>
                                <td class="fw-bold text-gray-900">{{ $user->city->title ?? '-' }} / {{ $user->country->title ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Vehicle Details Card -->
        <div class="card mb-7 shadow-sm">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4"><i class="ki-outline ki-car text-primary me-2"></i> بيانات المركبة واللوحة</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-7 gy-3">
                        <tbody>
                            <tr>
                                <th class="fw-bold text-gray-500 w-120px">رقم اللوحة</th>
                                <td class="fw-bold text-dark fs-6">{{ $driver->driver_cars->car_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">ماركة السيارة</th>
                                <td class="fw-bold text-gray-900">{{ $driver->driver_cars->car_brand ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">موديل السيارة</th>
                                <td class="fw-bold text-gray-900">{{ $driver->driver_cars->car_model ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">سنة الصنع</th>
                                <td class="fw-bold text-gray-900">{{ $driver->driver_cars->manufacture_year ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="fw-bold text-gray-500">لون السيارة</th>
                                <td class="fw-bold text-gray-900">{{ $driver->driver_cars->color ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Visual Document Comparison Grid & Audit History -->
    <div class="col-xl-8">
        <!-- Document Images Comparison Grid -->
        <div class="card mb-7 shadow-sm">
            <div class="card-header pt-6 pb-2 border-0 d-flex justify-content-between align-items-center">
                <h3 class="card-label fw-bold text-gray-900 fs-4">
                    <i class="ki-outline ki-eye text-primary me-2"></i> صور المرفقات والمستندات للمقارنة البصرية
                </h3>
                <span class="badge bg-light-primary text-primary fw-bold">المعاينة المباشرة بدون PDF</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- National ID Front & Back -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">بطاقة الرقم القومي (الوجه الأمامي)</h6>
                            <a href="{{ $documents['id_front'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['id_front'] }}" alt="ID Front" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">بطاقة الرقم القومي (الوجه الخلفي)</h6>
                            <a href="{{ $documents['id_back'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['id_back'] }}" alt="ID Back" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>

                    <!-- Driving License Front & Back -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">رخصة القيادة (الوجه الأمامي)</h6>
                            <a href="{{ $documents['license_front'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['license_front'] }}" alt="License Front" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">رخصة القيادة (الوجه الخلفي)</h6>
                            <a href="{{ $documents['license_back'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['license_back'] }}" alt="License Back" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>

                    <!-- Car License Front & Back -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">رخصة السيارة (الوجه الأمامي)</h6>
                            <a href="{{ $documents['car_license_front'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['car_license_front'] }}" alt="Car License Front" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">رخصة السيارة (الوجه الخلفي)</h6>
                            <a href="{{ $documents['car_license_back'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['car_license_back'] }}" alt="Car License Back" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>

                    <!-- Vehicle Photos -->
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">صورة السيارة (الأمامية)</h6>
                            <a href="{{ $documents['car_front'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['car_front'] }}" alt="Car Front" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light bg-opacity-50">
                            <h6 class="fw-bold text-gray-800 mb-2">صحيفة الفيش والتشبيه / التحليل</h6>
                            <a href="{{ $documents['criminal_record'] }}" target="_blank" class="d-block text-center border rounded bg-white p-2">
                                <img src="{{ $documents['criminal_record'] }}" alt="Criminal Record" class="img-fluid rounded" style="max-height: 200px; object-fit: contain;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Decision History Log -->
        <div class="card shadow-sm">
            <div class="card-header pt-6 pb-2 border-0">
                <h3 class="card-label fw-bold text-gray-900 fs-4">
                    <i class="ki-outline ki-clipboard-check text-primary me-2"></i> سجل وتاريخ قرارات التقديم السابقة
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border rounded-3 fs-7">
                        <thead class="table-light">
                            <tr>
                                <th>القرار المأخوذ</th>
                                <th>السبب المكتوب / ملاحظة القرار</th>
                                <th>المشرف المسئول</th>
                                <th>التاريخ والوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($driver->relationLoaded('registration_logs'))
                                @forelse($driver->registration_logs as $log)
                                    <tr>
                                        <td>
                                            @if($log->action == 'approved')
                                                <span class="badge bg-light-success text-success fw-bold"><i class="ki-outline ki-check-circle text-success me-1"></i> مقبول ومفعّل</span>
                                            @elseif($log->action == 'rejected')
                                                <span class="badge bg-light-danger text-danger fw-bold"><i class="ki-outline ki-cross-circle text-danger me-1"></i> مرفوض</span>
                                            @else
                                                <span class="badge bg-light-primary text-primary fw-bold">{{ $log->action }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ $log->reason ?? 'لا يوجد سبب مكتوب' }}
                                        </td>
                                        <td class="text-muted">
                                            <i class="ki-outline ki-user me-1 text-primary"></i>
                                            {{ $log->admin->name ?? $log->admin->full_name ?? 'مشرف النظام' }}
                                        </td>
                                        <td class="text-muted">
                                            {{ $log->created_at ? $log->created_at->format('Y-m-d H:i A') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">لا توجد سجلات قرارات سابقة لهذا السائق حتى الآن.</td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">لم يتم تشغيل أمر php artisan migrate الخاص بجدول السجلات على السيرفر بعد.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Reject Driver Application -->
<div class="modal fade" id="rejectApplicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.driver-applications.reject', $driver->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold text-white"><i class="ki-outline ki-cross-circle text-white me-2"></i>رفض طلب انضمام السائق</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center p-3 mb-4">
                    <i class="ki-outline ki-information-5 fs-2x me-3 text-warning"></i>
                    <div class="fs-7 text-gray-800">
                        سيتم إرسال سبب الرفض مباشرة لتطبيق السائق ليقوم بإعادة تصوير رفع المستندات المطلوبة.
                    </div>
                </div>

                <!-- Predefined Quick Reasons -->
                <div class="mb-3">
                    <label class="form-label fw-bold">أسباب رفض سريعة مجهزة:</label>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 text-dark fs-8" onclick="setReason('صورة رخصة القيادة غير واضحة، يرجى إعادة التثبت والرفع بشكل واضح.')">رخصة القيادة غير واضحة</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 text-dark fs-8" onclick="setReason('رخصة السيارة منتهية الصلاحية، يرجى رفع رخصة سارية.')">رخصة السيارة منتهية</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 text-dark fs-8" onclick="setReason('صورة الوجه الخلفي لبطاقة الرقم القومي غير واضحة أو مفقودة.')">الوجه الخلفي للبطاقة مفقود</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2 text-dark fs-8" onclick="setReason('عدم تطابق رقم السيارة المكتوب مع صورة رخصة السيارة.')">عدم تطابق رقم السيارة</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">سبب الرفض الموجه للسائق <span class="text-danger">*</span></label>
                    <textarea name="reason" id="rejection_reason_input" rows="4" class="form-control" placeholder="اكتب سبب الرفض بالتفصيل ليظهر في تطبيق السائق..." required>{{ $rejectionReason ?? $driver->latest_rejection_reason ?? '' }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-danger fw-bold">تأكيد رفض الطلب وتسجيل السبب</button>
            </div>
        </form>
    </div>
</div>

<script>
function setReason(text) {
    document.getElementById('rejection_reason_input').value = text;
}
</script>
@endsection
