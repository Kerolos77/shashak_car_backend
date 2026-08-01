@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-discount fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">إدارة أشكال الخصم والكوبونات الترويجية (Promo Coupons)</h2>
                        <div class="text-muted fs-6">إنشاء ومتابعة وتفعيل أكواد الخصم لتطبيقات شقشق للمستخدمين.</div>
                    </div>
                </div>
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary fw-bold">
                    <i class="ki-outline ki-plus fs-4 me-1"></i> إضافة كوبون خصم جديد
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 mb-6">
                <i class="ki-outline ki-check-circle fs-2x me-3 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-6">
                <div class="table-responsive">
                    <table class="table table-hover align-middle border rounded-3">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>كود الخصم</th>
                                <th>نوع وقيمة الخصم</th>
                                <th>الحد الأقصى للخصم</th>
                                <th>الحد الأدنى للطلب</th>
                                <th>الخدمة المخصصة</th>
                                <th>عدد مرات الاستخدام</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة (ON/OFF)</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
                                        <span class="badge bg-light-primary text-primary fw-bolder fs-6 px-3 py-2">
                                            {{ $row->code }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-success">
                                        @if($row->type === 'percentage')
                                            {{ floatval($row->value) }}%
                                        @else
                                            {{ number_format($row->value, 2) }} ج.م
                                        @endif
                                    </td>
                                    <td>{{ $row->max_discount ? number_format($row->max_discount, 2) . ' ج.م' : 'بدون حد أقصى' }}</td>
                                    <td>{{ $row->min_order ? number_format($row->min_order, 2) . ' ج.م' : 'بدون حد أدنى' }}</td>
                                    <td>
                                        @if($row->service)
                                            <span class="badge bg-light-info text-info">{{ $row->service->title }}</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">جميع الخدمات</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $row->used_count }}</span> / {{ $row->usage_limit ?? 'غير محدد' }}
                                    </td>
                                    <td>
                                        @if($row->expires_at)
                                            <span class="{{ $row->expires_at->isPast() ? 'text-danger fw-bold' : 'text-muted' }}">
                                                {{ $row->expires_at->format('Y-m-d H:i') }}
                                            </span>
                                        @else
                                            <span class="text-muted">مفتوح (دائم)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch form-check-custom form-check-solid justify-content-center">
                                            <input class="form-check-input h-20px w-35px coupon-status-toggle" 
                                                   type="checkbox" 
                                                   data-id="{{ $row->id }}" 
                                                   data-url="{{ route('admin.coupons.toggle-active', $row->id) }}" 
                                                   {{ $row->is_active ? 'checked' : '' }} />
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.coupons.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت تأكد من حذف هذا الكوبون؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-light-danger btn-sm">
                                                <i class="ki-outline ki-trash fs-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-6 text-muted">لا توجد كوبونات خصم حالياً</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $rows->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.coupon-status-toggle').on('change', function () {
        var $self = $(this);
        var url = $self.attr('data-url');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.success) {
                    toastr.success(res.message);
                } else {
                    $self.prop('checked', !$self.prop('checked'));
                }
            },
            error: function () {
                $self.prop('checked', !$self.prop('checked'));
                toastr.error('حدث خطأ أثناء تعديل حالة الكوبون');
            }
        });
    });
});
</script>
@endpush
