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
                                        <button type="button" 
                                                class="btn btn-icon btn-light-primary btn-sm me-1 btn-send-fcm" 
                                                data-id="{{ $row->id }}" 
                                                data-code="{{ $row->code }}"
                                                data-url="{{ route('admin.coupons.send-fcm', $row->id) }}"
                                                title="إرسال إشعار FCM للمستخدمين">
                                            <i class="ki-outline ki-send fs-4"></i>
                                        </button>
                                        <form action="{{ route('admin.coupons.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت تأكد من حذف هذا الكوبون؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-light-danger btn-sm" title="حذف الكوبون">
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

<!-- Modal: Send FCM Coupon Notification -->
<div class="modal fade" id="sendCouponFcmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light-primary p-3 rounded-circle">
                        <i class="ki-outline ki-send fs-2 text-primary"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">إرسال كوبون خصم عبر إشعار (FCM)</h4>
                        <span class="text-muted fs-7">الكوبون المستهدف: <strong id="modalCouponCode" class="text-primary fs-6"></strong></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendFcmForm">
                @csrf
                <div class="modal-body p-6">
                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-6">الفئة المستهدفة للإرسال <span class="text-danger">*</span></label>
                        <select name="target" id="fcmTargetSelect" class="form-select form-select-solid" required>
                            <option value="all_users">جميع العملاء النشطين (All Users)</option>
                            <option value="inactive_users">العملاء الخاملين (بدون رحلات خلال آخر 30 يوماً)</option>
                            <option value="active_vip">العملاء الأكثر نشاطاً وولاءً (5+ رحلات مكتملة)</option>
                            <option value="all_drivers">جميع السائقين (All Drivers)</option>
                            <option value="specific_user">عميل محدد (Specific User)</option>
                        </select>
                    </div>

                    <div class="mb-5 d-none" id="specificUserWrapper">
                        <label class="form-label fw-semibold fs-6">اختر العميل المستهدف <span class="text-danger">*</span></label>
                        <select name="user_id" id="fcmUserSelect" class="form-select form-select-solid">
                            <option value="">اختر العميل...</option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-6">عنوان الإشعار (Notification Title) <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="fcmTitle" class="form-control form-control-solid" required placeholder="مثال: خصم خاص لك من شقشق!" />
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-6">نص الرسالة (Notification Body) <span class="text-danger">*</span></label>
                        <textarea name="body" id="fcmBody" class="form-control form-control-solid" rows="3" required placeholder="مثال: استخدم الكوبون واحصل على خصم مميز في رحلتك القادمة!"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-6">رابط صورة الإشعار (اختياري)</label>
                        <input type="url" name="image_url" class="form-control form-control-solid" placeholder="https://example.com/banner.jpg" />
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitFcm">
                        <span class="indicator-label"><i class="ki-outline ki-send fs-4 me-1"></i> إرسال الإشعار الآن</span>
                        <span class="indicator-progress d-none">جاري الإرسال... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFcmUrl = '';

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

    $('.btn-send-fcm').on('click', function() {
        const couponCode = $(this).data('code');
        currentFcmUrl = $(this).data('url');

        $('#modalCouponCode').text(couponCode);
        $('#fcmTitle').val('خصم خاص لك من شقشق! 🎉');
        $('#fcmBody').val(`استخدم الكوبون (${couponCode}) الآن واحصل على خصم مميز في رحلتك القادمة! 🚗💨`);
        $('#fcmTargetSelect').val('all_users').trigger('change');

        const modal = new bootstrap.Modal(document.getElementById('sendCouponFcmModal'));
        modal.show();
    });

    $('#fcmTargetSelect').on('change', function() {
        if ($(this).val() === 'specific_user') {
            $('#specificUserWrapper').removeClass('d-none');
            loadUsersForSelect();
        } else {
            $('#specificUserWrapper').addClass('d-none');
        }
    });

    function loadUsersForSelect() {
        const $select = $('#fcmUserSelect');
        if ($select.children('option').length <= 1) {
            $.ajax({
                url: '{{ route("admin.coupons.search-users") }}',
                type: 'GET',
                success: function(users) {
                    $select.empty().append('<option value="">اختر العميل...</option>');
                    users.forEach(function(u) {
                        $select.append(`<option value="${u.id}">${u.name} - ${u.phone_number ?? 'بدون رقم'}</option>`);
                    });
                }
            });
        }
    }

    $('#sendFcmForm').on('submit', function(e) {
        e.preventDefault();
        if (!currentFcmUrl) return;

        const $btn = $('#btnSubmitFcm');
        $btn.find('.indicator-label').addClass('d-none');
        $btn.find('.indicator-progress').removeClass('d-none');
        $btn.prop('disabled', true);

        $.ajax({
            url: currentFcmUrl,
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    const modalEl = document.getElementById('sendCouponFcmModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                } else {
                    toastr.error(res.message || 'حدث خطأ أثناء الإرسال');
                }
            },
            error: function(err) {
                const msg = err.responseJSON ? err.responseJSON.message : 'حدث خطأ أثناء الاتصال بالسيرفر';
                toastr.error(msg);
            },
            complete: function() {
                $btn.find('.indicator-label').removeClass('d-none');
                $btn.find('.indicator-progress').addClass('d-none');
                $btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endpush
