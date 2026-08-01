@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">إعدادات النظام</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">إدارة ورسائل SMS والقوالب</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-5" role="alert">
        <i class="ki-outline ki-check-circle fs-3 me-2 text-success"></i>
        <div class="fw-bold">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('test_result'))
    <div class="alert alert-info alert-dismissible fade show mb-5" role="alert">
        <h5 class="alert-heading fw-bold mb-2">نتيجة تجربة الإرسال (Provider API Response):</h5>
        <pre class="bg-dark text-white p-3 rounded fs-7 mb-0">{{ is_array(session('test_result')) ? json_encode(session('test_result'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : session('test_result') }}</pre>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        
        <!-- Header Card -->
        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-sms fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">إدارة ورسائل SMS والقوالب المخصصة</h2>
                        <div class="text-muted fs-6">التحكم في بيانات بوابة الإرسال، تخصيص صياغة وقوالب رسائل OTP والشحنات، واختبار الإرسال.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-6">
                
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-6 fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_sms_config">
                            <i class="ki-outline ki-setting-2 fs-4 me-2"></i>إعدادات بوابة الإرسال (Credentials)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_sms_templates">
                            <i class="ki-outline ki-message-text-2 fs-4 me-2"></i>تخصيص قوالب الرسائل (SMS Templates)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_sms_test">
                            <i class="ki-outline ki-send fs-4 me-2"></i>تجربة إرسال رسالة (Test SMS)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab_sms_logs">
                            <i class="ki-outline ki-document fs-4 me-2"></i>سجل الرسائل المرسلة (Logs History)
                        </a>
                    </li>
                </ul>

                <form action="{{ route('admin.sms-settings.update') }}" method="POST">
                    @csrf

                    <div class="tab-content" id="smsTabContent">

                        <!-- TAB 1: SMS Credentials Config -->
                        <div class="tab-pane fade show active" id="tab_sms_config">
                            
                            <div class="form-check form-switch form-check-custom form-check-solid mb-6 p-3 bg-light rounded">
                                <input class="form-check-input h-30px w-50px" type="checkbox" name="sms_enabled" value="1" id="sms_enabled" {{ ($setting->sms_enabled ?? true) ? 'checked' : '' }} />
                                <label class="form-check-label fw-bolder text-gray-800 fs-5 ms-3" for="sms_enabled">
                                    تفعيل نظام إرسال الـ SMS النصية تلقائياً
                                </label>
                            </div>

                            <div class="row g-4 mb-6">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">رابط بوابة الإرسال (Base API URL)</label>
                                    <input type="url" name="sms_base_url" class="form-control form-control-solid" value="{{ old('sms_base_url', $setting->sms_base_url ?? 'http://smssmartegypt.com/sms/api') }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">اسم الراسل (Sender Name)</label>
                                    <input type="text" name="sms_sender" class="form-control form-control-solid" value="{{ old('sms_sender', $setting->sms_sender ?? 'Shakshak') }}" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">اسم المستخدم لدى بوابة SMS (Username)</label>
                                    <input type="text" name="sms_username" class="form-control form-control-solid" placeholder="أدخل اسم الحساب في Smart SMS Egypt" value="{{ old('sms_username', $setting->sms_username ?? env('SMS_USERNAME', '')) }}" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">كلمة المرور لدى بوابة SMS (Password)</label>
                                    <input type="password" name="sms_password" class="form-control form-control-solid" placeholder="أدخل كلمة المرور" value="{{ old('sms_password', $setting->sms_password ?? env('SMS_PASSWORD', '')) }}" />
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary fw-bold px-6">
                                    <i class="ki-outline ki-check fs-4 me-1"></i> حفظ التغيرات والإعدادات
                                </button>
                            </div>
                        </div>

                        <!-- TAB 2: Customize SMS Templates -->
                        <div class="tab-pane fade" id="tab_sms_templates">
                            
                            <div class="alert alert-light-primary d-flex align-items-center p-4 mb-6 rounded">
                                <i class="ki-outline ki-information-5 fs-2x text-primary me-4"></i>
                                <div class="fs-6">
                                    <strong class="d-block mb-1">تخصيص نصوص وقوالب الرسائل:</strong>
                                    يمكنك تعديل نص الرسالة المرسلة لكل حالة. اضغط على أي متغير ديناميكي (مثل <code>:otp</code> أو <code>:order_id</code>) لإدراجه تلقائياً داخل النص.
                                </div>
                            </div>

                            <div class="row g-6">
                                
                                <!-- Card 1: OTP Template -->
                                <div class="col-12">
                                    <div class="card border border-gray-300 shadow-none">
                                        <div class="card-header bg-light py-4">
                                            <h3 class="card-title fw-bolder fs-5 text-gray-800">
                                                <i class="ki-outline ki-key-square fs-3 text-warning me-2"></i> 1. قالب كود التفعيل والتحقق (OTP Template)
                                            </h3>
                                            <div class="card-toolbar">
                                                <span class="badge badge-light-warning fw-bold">تطبيقات العميل / السائق</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-5">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">نص رسالة الـ OTP:</label>
                                                <textarea name="sms_message_template" id="tpl_otp" class="form-control form-control-solid" rows="2" required>{{ old('sms_message_template', $setting->sms_message_template ?? 'كود تفعيل حسابك في شقشق هو: :otp') }}</textarea>
                                            </div>

                                            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                                                <span class="text-muted fs-7 me-2">المتغيرات المتاحة للإدراج:</span>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_otp" data-tag=":otp">
                                                    <code>:otp</code> (رمز التفعيل الرقمي)
                                                </button>
                                            </div>

                                            <div class="bg-light-secondary p-3 rounded border">
                                                <div class="text-muted fs-8 fw-bold mb-1"><i class="ki-outline ki-eye fs-7 me-1"></i> معاينة شكل الرسالة للمستلم (Preview):</div>
                                                <div id="preview_otp" class="fs-7 fw-semibold text-gray-800"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Receiver Delivery Shipping Template -->
                                <div class="col-12">
                                    <div class="card border border-gray-300 shadow-none">
                                        <div class="card-header bg-light py-4">
                                            <h3 class="card-title fw-bolder fs-5 text-gray-800">
                                                <i class="ki-outline ki-delivery fs-3 text-info me-2"></i> 2. قالب إشعار وصول الشحنة للمستلم (Receiver Delivery Notification)
                                            </h3>
                                            <div class="card-toolbar">
                                                <span class="badge badge-light-info fw-bold">عندما تتحرك الشحنة مع السائق</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-5">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">نص الرسالة عند تحرك الشحنة في الطريق:</label>
                                                <textarea name="sms_shipping_template" id="tpl_shipping" class="form-control form-control-solid" rows="3" required>{{ old('sms_shipping_template', $setting->sms_shipping_template ?? 'أهلاً بك، شحنتك رقم #:order_id مع السائق :driver_name في الطريق إليك. للتتبع المباشر استخدم الرابط: :tracking_link وكود الاستلام هو: :otp') }}</textarea>
                                            </div>

                                            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                                                <span class="text-muted fs-7 me-2">المتغيرات المتاحة للإدراج:</span>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping" data-tag=":order_id">
                                                    <code>:order_id</code> (رقم الشحنة)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping" data-tag=":driver_name">
                                                    <code>:driver_name</code> (اسم السائق)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping" data-tag=":tracking_link">
                                                    <code>:tracking_link</code> (رابط التتبع)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping" data-tag=":otp">
                                                    <code>:otp</code> (كود الاستلام)
                                                </button>
                                            </div>

                                            <div class="bg-light-secondary p-3 rounded border">
                                                <div class="text-muted fs-8 fw-bold mb-1"><i class="ki-outline ki-eye fs-7 me-1"></i> معاينة شكل الرسالة للمستلم (Preview):</div>
                                                <div id="preview_shipping" class="fs-7 fw-semibold text-gray-800"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Receiver Order Verification Template -->
                                <div class="col-12">
                                    <div class="card border border-gray-300 shadow-none">
                                        <div class="card-header bg-light py-4">
                                            <h3 class="card-title fw-bolder fs-5 text-gray-800">
                                                <i class="ki-outline ki-check-circle fs-3 text-success me-2"></i> 3. قالب كود تأكيد طلب الشحن للمستلم (Receiver Order Verification Code)
                                            </h3>
                                            <div class="card-toolbar">
                                                <span class="badge badge-light-success fw-bold">فور إنشاء طلب الشحن</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-5">
                                            <div class="mb-4">
                                                <label class="form-label fw-bold">نص رسالة كود تأكيد الطلب الموجه للمستلم:</label>
                                                <textarea name="sms_shipping_verification_template" id="tpl_shipping_verif" class="form-control form-control-solid" rows="3" required>{{ old('sms_shipping_verification_template', $setting->sms_shipping_verification_template ?? 'أهلاً بك، تم إنشاء طلب شحن جديد موجه إليك برقم #:order_id من العميل :sender_name. كود التأكيد الخاص بالطلب هو: :otp. يرجى تزويده للمرسل لتأكيد الطلب.') }}</textarea>
                                            </div>

                                            <div class="mb-3 d-flex align-items-center flex-wrap gap-2">
                                                <span class="text-muted fs-7 me-2">المتغيرات المتاحة للإدراج:</span>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping_verif" data-tag=":order_id">
                                                    <code>:order_id</code> (رقم الشحنة)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping_verif" data-tag=":sender_name">
                                                    <code>:sender_name</code> (اسم المرسل/العميل)
                                                </button>
                                                <button type="button" class="btn btn-sm btn-light-primary py-1 px-3 insert-tag" data-target="tpl_shipping_verif" data-tag=":otp">
                                                    <code>:otp</code> (كود التأكيد)
                                                </button>
                                            </div>

                                            <div class="bg-light-secondary p-3 rounded border">
                                                <div class="text-muted fs-8 fw-bold mb-1"><i class="ki-outline ki-eye fs-7 me-1"></i> معاينة شكل الرسالة للمستلم (Preview):</div>
                                                <div id="preview_shipping_verif" class="fs-7 fw-semibold text-gray-800"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-6">
                                <button type="submit" class="btn btn-primary fw-bold px-8">
                                    <i class="ki-outline ki-check fs-4 me-1"></i> حفظ القوالب المخصصة
                                </button>
                            </div>

                        </div>

                        <!-- TAB 3: Send Test SMS -->
                        <div class="tab-pane fade" id="tab_sms_test">
                            <div class="max-w-600px">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">رقم الهاتف التجريبي (مثال: 01012345678)</label>
                                    <input type="text" name="mobile" form="test_sms_form" class="form-control form-control-solid" placeholder="01012345678" required />
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">نص الرسالة التجريبية</label>
                                    <textarea name="message" form="test_sms_form" class="form-control form-control-solid" rows="3" required>رسالة تجريبية من لوحة تحكم شقشق - للتأكد من ربط بوابة SMS بنجاح.</textarea>
                                </div>
                                <button type="submit" form="test_sms_form" class="btn btn-success fw-bold">
                                    <i class="ki-outline ki-send fs-4 me-1"></i> إرسال الآن واختبار البوابة
                                </button>
                            </div>
                        </div>

                    </div>
                </form>

                <!-- Hidden Independent Form for Test SMS -->
                <form id="test_sms_form" action="{{ route('admin.sms-settings.send-test') }}" method="POST">
                    @csrf
                </form>

                <!-- TAB 4: SMS Logs History -->
                <div class="tab-pane fade" id="tab_sms_logs">
                    <div class="table-responsive mt-4">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم المستلم</th>
                                    <th>النوع</th>
                                    <th>نص الرسالة</th>
                                    <th>الحالة</th>
                                    <th>استجابة البوابة</th>
                                    <th>التاريخ والوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td class="fw-bold">{{ $log->mobile }}</td>
                                        <td>
                                            @if($log->type === 'shipping' || $log->type === 'shipping_delivery')
                                                <span class="badge bg-light-info text-info">إشعار شحنة</span>
                                            @elseif($log->type === 'shipping_receiver_verification')
                                                <span class="badge bg-light-success text-success">تأكيد شحنة</span>
                                            @elseif($log->type === 'otp')
                                                <span class="badge bg-light-warning text-warning">كود OTP</span>
                                            @else
                                                <span class="badge bg-light-primary text-primary">{{ $log->type }}</span>
                                            @endif
                                        </td>
                                        <td class="fs-7 text-gray-800" style="max-width: 350px; white-space: normal;">{{ $log->message }}</td>
                                        <td>
                                            @if($log->status === 'success')
                                                <span class="badge badge-light-success fw-bold">نجح الإرسال ✔</span>
                                            @elseif($log->status === 'disabled')
                                                <span class="badge badge-light-dark fw-bold">معطل بالنظام ⏸</span>
                                            @else
                                                <span class="badge badge-light-danger fw-bold">فشل الإرسال ✖</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code class="fs-8 text-dark">{{ is_array($log->gateway_response) ? json_encode($log->gateway_response, JSON_UNESCAPED_UNICODE) : $log->gateway_response }}</code>
                                        </td>
                                        <td class="small text-muted">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-6">لا توجد سجلات رسائل مرسلة حتى الآن.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Live Previews logic
    const updatePreview = function (textareaId, previewId, replacements) {
        const textarea = document.getElementById(textareaId);
        const preview = document.getElementById(previewId);
        if (!textarea || !preview) return;

        let val = textarea.value;
        for (const [key, sample] of Object.entries(replacements)) {
            val = val.replaceAll(key, '<span class="text-primary fw-bolder">' + sample + '</span>');
        }
        preview.innerHTML = val || '<em class="text-muted">لم يتم إدخال نص بعد...</em>';
    };

    const renderAllPreviews = function () {
        updatePreview('tpl_otp', 'preview_otp', {
            ':otp': '4921'
        });
        updatePreview('tpl_shipping', 'preview_shipping', {
            ':order_id': '1052',
            ':driver_name': 'أحمد محمود',
            ':tracking_link': 'https://shakshak.net/track/1052',
            ':otp': '8392'
        });
        updatePreview('tpl_shipping_verif', 'preview_shipping_verif', {
            ':order_id': '1052',
            ':sender_name': 'محمد علي',
            ':otp': '6140'
        });
    };

    ['tpl_otp', 'tpl_shipping', 'tpl_shipping_verif'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', renderAllPreviews);
        }
    });

    // Tag click insertion
    document.querySelectorAll('.insert-tag').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const tag = this.getAttribute('data-tag');
            const textarea = document.getElementById(targetId);
            if (!textarea) return;

            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;

            textarea.value = text.substring(0, start) + ' ' + tag + ' ' + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + tag.length + 2;

            renderAllPreviews();
        });
    });

    renderAllPreviews();
});
</script>
@endpush

@endsection
