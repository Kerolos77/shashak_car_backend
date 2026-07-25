@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted"> {{ trans('cruds.user.title') }} </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.view') }}</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-5" role="alert">
        <i class="ki-outline ki-check-circle fs-3 me-2 text-success"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        
        <!-- Header & Quick Actions -->
        <div class="card mb-6 shadow-sm">
            <div class="card-body p-6 d-flex flex-wrap align-items-center justify-content-between gap-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="symbol symbol-70px symbol-circle border border-primary border-opacity-20 p-1">
                        <img src="{{ asset($user->profile_pic ?? 'assets/media/avatars/blank.png') }}" alt="{{ $user->name }}" style="object-fit: cover;">
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                            {{ $user->name }}
                            @if($user->is_vip)
                                <span class="badge badge-light-warning text-dark fw-bold" title="عميل مميز (VIP)">⭐ VIP Member</span>
                            @endif
                        </h2>
                        <div class="text-muted fs-6">{{ $user->email }} | {{ $user->phone_number }}</div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <!-- Toggle VIP Button -->
                    <form action="{{ route('admin.users.toggle-vip', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $user->is_vip ? 'btn-light-warning' : 'btn-outline-warning' }} fw-bold">
                            <i class="ki-outline ki-star fs-5 me-1"></i> {{ $user->is_vip ? 'إلغاء شارة VIP' : 'تمييز كـ VIP ⭐' }}
                        </button>
                    </form>

                    <!-- Add Wallet Balance Button -->
                    <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#userWalletModal">
                        <i class="ki-outline ki-wallet fs-5 me-1"></i> إضافة رصيد محفظة
                    </button>

                    <a href="{{ route('admin.users.export-pdf', $user->id) }}" class="btn btn-sm btn-danger px-4" target="_blank">
                        <i class="ki-outline ki-document fs-5 me-1"></i> تصدير PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Order Statistics Cards -->
        @if(isset($orderStats))
            <div class="row g-4 mb-6">
                <div class="col-sm-6 col-md-3">
                    <div class="card bg-light-primary border-0 p-4 rounded-3 text-center shadow-sm">
                        <span class="fs-6 text-muted fw-semibold d-block mb-1">إجمالي رحلات العميل</span>
                        <span class="fs-2x fw-bold text-primary">{{ $orderStats['total'] }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card bg-light-success border-0 p-4 rounded-3 text-center shadow-sm">
                        <span class="fs-6 text-muted fw-semibold d-block mb-1">الرحلات المكتملة</span>
                        <span class="fs-2x fw-bold text-success">{{ $orderStats['completed'] }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card bg-light-danger border-0 p-4 rounded-3 text-center shadow-sm">
                        <span class="fs-6 text-muted fw-semibold d-block mb-1">الرحلات الملغاة</span>
                        <span class="fs-2x fw-bold text-danger">{{ $orderStats['canceled'] }}</span>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card bg-light-info border-0 p-4 rounded-3 text-center shadow-sm">
                        <span class="fs-6 text-muted fw-semibold d-block mb-1">إجمالي ما أنفقه</span>
                        <span class="fs-2x fw-bold text-info">{{ number_format($orderStats['total_spent'], 0) }}ج.م</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">{{ trans('global.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_overview_referrals_tab">
                            {{ trans('cruds.referral.tab_title') }}
                            <span class="badge badge-light-primary ms-2">{{ $user->referrals->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_identity_tab">
                            توثيق الهوية بالذكاء الاصطناعي
                            @if($user->identity)
                                @if($user->identity->status === 'verified')
                                    <span class="badge badge-light-success ms-2">موثق</span>
                                @else
                                    <span class="badge badge-light-danger ms-2">فشل التحقق</span>
                                @endif
                            @else
                                <span class="badge badge-light-dark ms-2">غير موثق</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_reviews_tab">
                            التقييمات والمراجعات ({{ isset($reviews) ? $reviews->count() : 0 }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_audit_tab">
                            سجل إجراءات الإدارة ({{ isset($auditLogs) ? $auditLogs->count() : 0 }})
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">

                    <!-- TAB 1: Overview -->
                    <div class="tab-pane fade show active" id="kt_user_view_overview_tab">
                        <div class="card card-flush mb-6">
                            <div class="card-header mt-4">
                                <div class="card-title flex-column">
                                    <h3 class="mb-1">{{ trans('cruds.user.fields.user_information') }}</h3>
                                </div>
                            </div>
                            <div class="card-body p-6 pt-0">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.id') }}</th>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.name') }}</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.email') }}</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>رصيد المحفظة الحركية</th>
                                            <td class="fw-bold text-success fs-5">{{ number_format($user->wallet_amount ?? 0, 2) }} ج.م</td>
                                        </tr>
                                        <tr>
                                            <th>متوسط تقييم العميل</th>
                                            <td class="fw-bold text-dark">⭐ {{ number_format($user->rating ?? 5.0, 2) }} / 5.0</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.referral.fields.referral_code') }}</th>
                                            <td><span class="badge badge-light-success fw-bolder fs-6">{{ $user->referral_code }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.referral.fields.referral_by') }}</th>
                                            <td>
                                                @if($user->referrer)
                                                    <a href="{{ route('admin.users.show', $user->referrer->id) }}">{{ $user->referrer->name }}</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.roles') }}</th>
                                            <td>
                                                @foreach($user->roles as $key => $role)
                                                    <span class="badge badge-info">{{ $role->title }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Referrals -->
                    <div class="tab-pane fade" id="kt_user_view_overview_referrals_tab">
                        <div class="card card-flush">
                            <div class="card-body pt-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.id') }}</th>
                                            <th>{{ trans('cruds.user.fields.name') }}</th>
                                            <th>{{ trans('cruds.user.fields.email') }}</th>
                                            <th>{{ trans('cruds.user.fields.created_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->referrals as $ref)
                                            <tr>
                                                <td>{{ $ref->id }}</td>
                                                <td>{{ $ref->name }}</td>
                                                <td>{{ $ref->email }}</td>
                                                <td>{{ $ref->created_at }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">لا يوجد أحيلوا عن طريق هذا العميل.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Identity Verification -->
                    <div class="tab-pane fade" id="kt_user_view_identity_tab">
                        <div class="card card-flush">
                            <div class="card-body pt-4">
                                @if($user->identity)
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>حالة التوثيق</th>
                                                <td>
                                                    @if($user->identity->status === 'verified')
                                                        <span class="badge badge-light-success fw-bolder fs-6">موثق بنجاح</span>
                                                    @else
                                                        <span class="badge badge-light-danger fw-bolder fs-6">فشل التحقق</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>الرقم القومي المستخرج</th>
                                                <td><span class="badge badge-light-dark fw-bolder fs-6">{{ $user->identity->id_number }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-10">
                                        <p class="text-gray-500 fs-5">لم يقم هذا العميل برفع أي مستندات أو طلب توثيق الهوية حتى الآن.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: Reviews & Deletion -->
                    <div class="tab-pane fade" id="kt_user_view_reviews_tab">
                        <div class="card card-flush p-4">
                            <h4 class="fw-bold mb-4">التقييمات المكتوبة عن العميل</h4>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border rounded-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>الكاتب</th>
                                            <th>التقييم</th>
                                            <th>التعليق</th>
                                            <th>التاريخ</th>
                                            <th>إجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($reviews))
                                            @forelse($reviews as $rev)
                                                <tr>
                                                    <td class="fw-bold">{{ $rev->fromUser->full_name ?? $rev->fromUser->name ?? 'مستخدم #' . $rev->from_user_id }}</td>
                                                    <td><span class="badge bg-light-warning text-dark fw-bold">⭐ {{ $rev->rating }}</span></td>
                                                    <td class="small text-muted">{{ $rev->comment ?? 'لا يوجد' }}</td>
                                                    <td class="small text-muted">{{ $rev->created_at ? $rev->created_at->format('Y-m-d H:i') : '-' }}</td>
                                                    <td>
                                                        <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('هل أنت ممتأكد من حذف هذا التقييم الكيدي؟ سيتم إعادة حساب المتوسط تلقائياً.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="حذف التقييم">
                                                                <i class="ki-outline ki-trash fs-5"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">لا توجد تقييمات مكتوبة لهذا العميل.</td>
                                                </tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: Admin Audit Trail -->
                    <div class="tab-pane fade" id="kt_user_view_audit_tab">
                        <div class="card card-flush p-4">
                            <h4 class="fw-bold mb-4">سجل إجراءات الإدارة على حساب العميل</h4>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle border rounded-3">
                                    <thead class="table-light">
                                        <tr>
                                            <th>المشرف / Admin</th>
                                            <th>الإجراء</th>
                                            <th>الملاحظات</th>
                                            <th>التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($auditLogs))
                                            @forelse($auditLogs as $log)
                                                <tr>
                                                    <td class="fw-bold">{{ $log->admin->name ?? $log->admin->email ?? 'مشرف النظام' }}</td>
                                                    <td><span class="badge bg-light-primary text-primary fw-bold">{{ $log->action }}</span></td>
                                                    <td class="small text-dark">{{ $log->notes }}</td>
                                                    <td class="small text-muted">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">لا توجد سجلات سابقة للمشرفين على هذا الحساب.</td>
                                                </tr>
                                            @endforelse
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Action Buttons -->
                <div class="d-flex justify-content-end mt-4">
                    @can('user_edit')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: User Wallet Top-up -->
<div class="modal fade" id="userWalletModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.users.add-wallet', $user->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">إضافة / خصم رصيد في محفظة العميل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">المبلغ (جنية مصري)</label>
                    <input type="number" step="0.5" name="amount" class="form-control" placeholder="أدخل المبلغ (استخدم سالب - للخصم)" required>
                    <small class="text-muted">أدخل 50 للإضافة أو -20 للخصم.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">سبب الإضافة/الخصم</label>
                    <input type="text" name="notes" class="form-control" placeholder="مثال: تعويض عن رحلة / هدية شحن">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary fw-bold">حفظ وتعديل الرصيد</button>
            </div>
        </form>
    </div>
</div>

@endsection
