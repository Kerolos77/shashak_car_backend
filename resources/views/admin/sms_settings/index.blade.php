@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">إعدادات النظام</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">إدارة ورسائل SMS</li>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-5" role="alert">
        <i class="ki-outline ki-check-circle fs-3 me-2 text-success"></i>
        <div>{{ session('success') }}</div>
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
        
        <!-- Header -->
        <div class="card mb-6 shadow-sm">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-3 rounded-circle">
                        <i class="ki-outline ki-sms fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1">إدارة نظام ورسائل SMS النصية</h2>
                        <div class="text-muted fs-6">التحكم في بيانات بوابة الإرسال، الرسائل التجريبية، ومتابعة سجلات الوصول للشحنات والأكواد.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-6">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-6 fs-6 fw-bold">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab_sms_config">
                            <i class="ki-outline ki-setting-2 fs-4 me-2"></i>إعدادات بوابة الإرسال (Credentials)
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

                <div class="tab-content" id="smsTabContent">

                    <!-- TAB 1: SMS Credentials Config -->
                    <div class="tab-pane fade show active" id="tab_sms_config">
                        <form action="{{ route('admin.sms-settings.update') }}" method="POST">
                            @csrf
                            
                            <div class="form-check form-switch form-check-custom form-check-solid mb-6">
                                <input class="form-check-input h-30px w-50px" type="checkbox" name="sms_enabled" value="1" id="sms_enabled" {{ ($setting->sms_enabled ?? true) ? 'checked' : '' }} />
                                <label class="form-check-label fw-bold text-gray-800 fs-5 ms-3" for="sms_enabled">
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

                            <div class="mb-6">
                                <label class="form-label fw-bold">قالب رسالة كود التفعيل (OTP Template)</label>
                                <input type="text" name="sms_message_template" class="form-control form-control-solid" value="{{ old('sms_message_template', $setting->sms_message_template ?? 'كود تفعيل حسابك في شقشق هو: :otp') }}" />
                                <small class="text-muted">استخدم <code>:otp</code> لمكان الكود الرقمي.</small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary fw-bold px-6">
                                    <i class="ki-outline ki-check fs-4 me-1"></i> حفظ التغيرات والإعدادات
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- TAB 2: Send Test SMS -->
                    <div class="tab-pane fade" id="tab_sms_test">
                        <form action="{{ route('admin.sms-settings.send-test') }}" method="POST" class="max-w-600px">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">رقم الهاتف التجريبي (مثال: 01012345678)</label>
                                <input type="text" name="mobile" class="form-control form-control-solid" placeholder="01012345678" required />
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">نص الرسالة التجريبية</label>
                                <textarea name="message" class="form-control form-control-solid" rows="3" required>رسالة تجريبية من لوحة تحكم شقشق - للتأكد من ربط بوابة SMS بنجاح.</textarea>
                            </div>
                            <button type="submit" class="btn btn-success fw-bold">
                                <i class="ki-outline ki-send fs-4 me-1"></i> إرسال الآن واختبار البوابة
                            </button>
                        </form>
                    </div>

                    <!-- TAB 3: SMS Logs History -->
                    <div class="tab-pane fade" id="tab_sms_logs">
                        <div class="table-responsive">
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
                                                @if($log->type === 'shipping')
                                                    <span class="badge bg-light-info text-info">شحن بضائع</span>
                                                @elseif($log->type === 'otp')
                                                    <span class="badge bg-light-warning text-warning">كود OTP</span>
                                                @else
                                                    <span class="badge bg-light-primary text-primary">{{ $log->type }}</span>
                                                @endif
                                            </td>
                                            <td class="fs-7 text-gray-800" style="max-width: 300px; white-space: normal;">{{ $log->message }}</td>
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
</div>

@endsection
