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
                                                data-type="{{ $row->type }}"
                                                data-value="{{ floatval($row->value) }}"
                                                data-max-discount="{{ $row->max_discount ? floatval($row->max_discount) : 0 }}"
                                                data-min-order="{{ $row->min_order ? floatval($row->min_order) : 0 }}"
                                                data-user-limit="{{ $row->user_limit }}"
                                                data-expires-at="{{ $row->expires_at ? $row->expires_at->format('Y-m-d H:i') : '' }}"
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
    <div class="modal-dialog modal-dialog-centered mw-700px">
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

                    <!-- Template Selector for Marketing -->
                    <div class="mb-5 bg-light-primary p-4 rounded-3 border border-primary border-opacity-25">
                        <label class="form-label fw-bolder fs-6 text-primary d-flex align-items-center justify-content-between mb-2">
                            <span><i class="ki-outline ki-element-plus me-1 fs-5"></i> اختر قالب الإشعار (Marketing Template)</span>
                            <span class="badge bg-primary text-white fs-9">فريق التسويق</span>
                        </label>
                        <select id="fcmTemplateSelect" class="form-select form-select-solid border-primary border-opacity-25 fw-bold">
                            <option value="detailed">🎉 قالب شامل (يتضمن كود الخصم، النسبة/المبلغ، الحد الأقصى، الصلاحية، وعدد الاستخدامات)</option>
                            <option value="urgent">⏳ قالب عاجل (تذكير باقتراب انتهاء الكوبون)</option>
                            <option value="vip">⭐ قالب عميل مميز (مكافأة ولاء وحصرية)</option>
                            <option value="simple">🚗 قالب سريع مبسط</option>
                            <option value="custom">✏️ قالب مخصص (إدخال وتعديل حر)</option>
                        </select>
                        <div class="fs-8 text-muted mt-2">يتم تجهيز تفاصيل الكوبون (الخصم، الحد الأقصى، الصلاحية، الاستخدامات) تلقائياً بناءً على بيانات الكوبون.</div>
                    </div>

                    <!-- Target Segment -->
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

                    <div class="mb-5 d-none p-4 bg-light rounded-3 border" id="specificUserWrapper">
                        <label class="form-label fw-bolder fs-6 mb-2 text-dark">
                            <i class="ki-outline ki-user me-1 text-primary"></i> البحث واختيار العميل المستهدف <span class="text-danger">*</span>
                        </label>
                        
                        <!-- Hidden input for selected user_id -->
                        <input type="hidden" name="user_id" id="fcmSelectedUserId" value="" />

                        <!-- Search Box Input -->
                        <div class="position-relative mb-3">
                            <input type="text" 
                                   id="userSearchInput" 
                                   class="form-control form-control-solid pe-10" 
                                   placeholder="🔍 اكتب للبحث بالاسم، رقم الموبايل، أو البريد الإلكتروني..." 
                                   autocomplete="off" />
                            <span id="userSearchSpinner" class="spinner-border spinner-border-sm position-absolute top-50 end-0 translate-middle-y me-3 d-none text-primary"></span>
                        </div>

                        <!-- Results List Box -->
                        <div id="userResultsList" class="bg-white rounded border shadow-sm p-2 mb-3" style="max-height: 240px; overflow-y: auto;">
                            <div class="text-muted p-3 text-center fs-7" id="userListPlaceholder">
                                <span class="spinner-border spinner-border-sm me-2"></span> جاري تحميل العملاء...
                            </div>
                        </div>

                        <!-- Selected User Banner -->
                        <div id="selectedUserBanner" class="p-3 bg-light-primary rounded border border-primary border-opacity-25 d-none">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fs-9 text-primary fw-bold text-uppercase mb-1">✓ العميل المختار للإرسال:</div>
                                    <div class="fw-bolder fs-6 text-dark" id="bannerUserName">-</div>
                                    <div class="text-muted fs-7 mt-1">
                                        <span id="bannerUserPhone" class="me-3"><i class="ki-outline ki-phone me-1"></i> -</span>
                                        <span id="bannerUserEmail"><i class="ki-outline ki-sms me-1"></i> -</span>
                                    </div>
                                </div>
                                <div id="bannerFcmBadge"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-6">عنوان الإشعار (Notification Title) <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="fcmTitle" class="form-control form-control-solid" required placeholder="مثال: خصم خاص لك من شقشق!" />
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-semibold fs-6">نص الرسالة (Notification Body) <span class="text-danger">*</span></label>
                        <textarea name="body" id="fcmBody" class="form-control form-control-solid" rows="4" required placeholder="مثال: استخدم الكوبون واحصل على خصم مميز في رحلتك القادمة!"></textarea>
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
    let currentCouponData = {};
    let currentFcmUrl = '';
    let searchTimer = null;

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
        const $btn = $(this);
        currentFcmUrl = $btn.data('url');

        currentCouponData = {
            code: $btn.data('code'),
            type: $btn.data('type'),
            value: $btn.data('value'),
            maxDiscount: $btn.data('max-discount'),
            minOrder: $btn.data('min-order'),
            userLimit: $btn.data('user-limit'),
            expiresAt: $btn.data('expires-at')
        };

        $('#modalCouponCode').text(currentCouponData.code);
        $('#fcmTargetSelect').val('all_users').trigger('change');
        $('#fcmTemplateSelect').val('detailed').trigger('change');

        const modal = new bootstrap.Modal(document.getElementById('sendCouponFcmModal'));
        modal.show();
    });

    $('#fcmTemplateSelect').on('change', function() {
        const templateKey = $(this).val();
        if (templateKey === 'custom') return;

        const code = currentCouponData.code || '';
        const type = currentCouponData.type || 'percentage';
        const val = currentCouponData.value || 0;
        const maxDiscount = currentCouponData.maxDiscount || 0;
        const userLimit = currentCouponData.userLimit || 1;
        const expiresAt = currentCouponData.expiresAt || '';

        const valueStr = (type === 'percentage') ? `${val}%` : `${val} ج.م`;
        const maxDiscountStr = (maxDiscount > 0) ? `${maxDiscount} ج.م` : 'بدون حد أقصى';
        const expiresStr = expiresAt ? `حتى ${expiresAt}` : 'صالح لفترة مفتوحة';
        const limitStr = `${userLimit} استخدامات لكل عميل`;

        let title = '';
        let body = '';

        if (templateKey === 'detailed') {
            title = `خصم ${valueStr} بكود (${code}) على رحلتك! 🎉`;
            body = `احصل على خصم ${valueStr} (حد أقصى ${maxDiscountStr}) عند استخدام كود الخصم (${code}). الكوبون متاح ${expiresStr} وبحد مسموح ${limitStr}. احجز رحلتك الآن! 🚗💨`;
        } else if (templateKey === 'urgent') {
            title = `⏳ سارع بالاستفادة! خصم ${valueStr} ينتهي قريباً`;
            body = `فرصة لا تعوض! كود الخصم (${code}) يعطيك خصم ${valueStr} (حتى ${maxDiscountStr}). العرض ${expiresStr}. استعمله قبل الانتهاء! ⏰`;
        } else if (templateKey === 'vip') {
            title = `⭐ خصم حصري لعملاء شقشق بقيمة ${valueStr}!`;
            body = `لأنك عميل خاص، استخدم كود الخصم (${code}) للحصول على خصم ${valueStr} في رحلتك القادمة. الأقصى للخصم ${maxDiscountStr} متاح لـ ${limitStr}. 🚀`;
        } else if (templateKey === 'simple') {
            title = `خصم خاص لك من شقشق! 🎉`;
            body = `استخدم الكوبون (${code}) الآن واحصل على خصم ${valueStr} في رحلتك القادمة! 🚗💨`;
        }

        $('#fcmTitle').val(title);
        $('#fcmBody').val(body);
    });

    $('#fcmTargetSelect').on('change', function() {
        if ($(this).val() === 'specific_user') {
            $('#specificUserWrapper').removeClass('d-none');
            $('#userSearchInput').val('');
            $('#fcmSelectedUserId').val('');
            $('#selectedUserBanner').addClass('d-none');
            loadUsersForSelect('');
        } else {
            $('#specificUserWrapper').addClass('d-none');
            $('#fcmSelectedUserId').val('');
        }
    });

    $('#userSearchInput').on('keyup input', function() {
        clearTimeout(searchTimer);
        const query = $(this).val();
        $('#userSearchSpinner').removeClass('d-none');
        searchTimer = setTimeout(function() {
            loadUsersForSelect(query);
        }, 300);
    });

    function loadUsersForSelect(query = '') {
        const $list = $('#userResultsList');
        $('#userSearchSpinner').removeClass('d-none');

        $.ajax({
            url: '{{ route("admin.coupons.search-users") }}',
            type: 'GET',
            data: { q: query },
            success: function(users) {
                $list.empty();
                if (users.length === 0) {
                    $list.html('<div class="text-danger p-3 text-center fs-7">❌ لم يتم العثور على عملاء يطابقون البحث</div>');
                } else {
                    const currentSelectedId = $('#fcmSelectedUserId').val();
                    users.forEach(function(u) {
                        const phone = u.phone_number ? u.phone_number : 'بدون رقم';
                        const email = u.email ? u.email : 'بدون إيميل';
                        const hasFcm = u.fcm_token ? true : false;
                        const isSelected = u.id == currentSelectedId;

                        const fcmBadgeHtml = hasFcm 
                            ? '<span class="badge bg-light-success text-success fw-bold fs-8">🟢 يستقبل FCM</span>'
                            : '<span class="badge bg-light-danger text-danger fw-bold fs-8">🔴 بدون FCM</span>';

                        const itemClass = isSelected ? 'user-item-card p-3 rounded mb-2 border cursor-pointer bg-light-primary border-primary shadow-sm' : 'user-item-card p-3 rounded mb-2 border cursor-pointer hover-bg-light';

                        $list.append(`
                            <div class="${itemClass}" 
                                 data-id="${u.id}" 
                                 data-name="${u.name}" 
                                 data-phone="${phone}" 
                                 data-email="${email}" 
                                 data-fcm="${hasFcm ? 1 : 0}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bolder text-dark fs-6">👤 ${u.name} <span class="text-muted fs-8">(ID: ${u.id})</span></div>
                                        <div class="text-muted fs-7 mt-1">
                                            <span class="me-3"><i class="ki-outline ki-phone me-1"></i>${phone}</span>
                                            <span><i class="ki-outline ki-sms me-1"></i>${email}</span>
                                        </div>
                                    </div>
                                    <div>${fcmBadgeHtml}</div>
                                </div>
                            </div>
                        `);
                    });

                    // Auto select first match if user typed search query and only 1 result returned
                    if (users.length === 1 && query.length > 0) {
                        $list.find('.user-item-card').first().trigger('click');
                    }
                }
            },
            error: function() {
                $list.html('<div class="text-danger p-3 text-center fs-7">حدث خطأ أثناء تحميل العملاء</div>');
            },
            complete: function() {
                $('#userSearchSpinner').addClass('d-none');
            }
        });
    }

    $(document).on('click', '.user-item-card', function() {
        $('.user-item-card').removeClass('bg-light-primary border-primary shadow-sm');
        $(this).addClass('bg-light-primary border-primary shadow-sm');

        const userId = $(this).data('id');
        const name = $(this).data('name');
        const phone = $(this).data('phone');
        const email = $(this).data('email');
        const hasFcm = $(this).data('fcm') == 1;

        $('#fcmSelectedUserId').val(userId);

        $('#bannerUserName').text(`👤 ${name} (ID: ${userId})`);
        $('#bannerUserPhone').html('<i class="ki-outline ki-phone me-1"></i> ' + phone);
        $('#bannerUserEmail').html('<i class="ki-outline ki-sms me-1"></i> ' + email);

        if (hasFcm) {
            $('#bannerFcmBadge').html('<span class="badge bg-success text-white fw-bold fs-7">🟢 جاهز لاستقبال الإشعارات</span>');
        } else {
            $('#bannerFcmBadge').html('<span class="badge bg-danger text-white fw-bold fs-7">🔴 هذا العميل لا يملك FCM</span>');
        }

        $('#selectedUserBanner').removeClass('d-none');
    });

    $('#sendFcmForm').on('submit', function(e) {
        e.preventDefault();
        if (!currentFcmUrl) return;

        if ($('#fcmTargetSelect').val() === 'specific_user' && !$('#fcmSelectedUserId').val()) {
            toastr.error('يرجى اختيار عميل من القائمة بالضغط عليه أولاً!');
            return false;
        }

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
