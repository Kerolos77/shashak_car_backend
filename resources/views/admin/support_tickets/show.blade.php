@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-message-text-2 fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">تفاصيل تذكرة الدعم والشكوى {{ $ticket->ticket_number }}</h2>
                        <div class="text-muted fs-6">مراجعة وتغيير حالة التذكرة وإضافة ملاحظات الدعم الفني.</div>
                    </div>
                </div>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-light fw-bold">
                    <i class="ki-outline ki-arrow-right fs-4 me-1"></i> العودة للتذاكر
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 mb-6">
                <i class="ki-outline ki-check-circle fs-2x me-3 text-success"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        @endif

        <div class="row g-6">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-6">
                    <div class="card-header bg-light py-4">
                        <h3 class="card-title fw-bolder mb-0 text-gray-800">تفاصيل الموضوع والشكوى</h3>
                    </div>
                    <div class="card-body p-6">
                        <h4 class="fw-bolder mb-4 text-primary">{{ $ticket->subject }}</h4>
                        <div class="p-4 bg-light rounded border text-gray-800 fs-6 leading-relaxed mb-6">
                            {!! nl2br(e($ticket->description)) !!}
                        </div>

                        @if($ticket->admin_notes)
                            <div class="mb-4">
                                <h5 class="fw-bold text-gray-800">ملاحظات رد الدعم الفني السابقة:</h5>
                                <div class="p-3 bg-light-warning text-dark border border-warning rounded">
                                    {!! nl2br(e($ticket->admin_notes)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-4">
                        <h3 class="card-title fw-bolder mb-0 text-gray-800">تحديث حالة التذكرة وإضافة الرد</h3>
                    </div>
                    <div class="card-body p-6">
                        <form action="{{ route('admin.tickets.update-status', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label required fw-bold">حالة التذكرة</label>
                                <select name="status" class="form-select form-select-solid" required>
                                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>مفتوحة (جديدة)</option>
                                    <option value="in_review" {{ $ticket->status === 'in_review' ? 'selected' : '' }}>قيد المراجعة والتحقيق</option>
                                    <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>تم الحل والتعويض (Resolved)</option>
                                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>مغلقة (Closed)</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label class="form-label fw-bold">إضافة رد / ملاحظات الإدارة</label>
                                <textarea name="admin_notes" class="form-control form-control-solid" rows="4" placeholder="اكتب رد الإدارة أو تفاصيل إجراء التعويض هنا...">{{ $ticket->admin_notes }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="ki-outline ki-check fs-4 me-1"></i> حفظ وتحديث التذكرة
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-6">
                    <div class="card-header bg-light py-4">
                        <h3 class="card-title fw-bolder mb-0 text-gray-800">بيانات صاحب الشكوى والطلب</h3>
                    </div>
                    <div class="card-body p-6">
                        <div class="mb-4">
                            <label class="text-muted fs-7 d-block">صاحب الشكوى (العميل):</label>
                            <span class="fw-bolder fs-6 text-gray-800">{{ $ticket->user->name ?? 'غير محدد' }}</span>
                            <div class="text-muted fs-7">📱 {{ $ticket->user->phone ?? '' }}</div>
                        </div>

                        @if($ticket->driver)
                            <div class="mb-4">
                                <label class="text-muted fs-7 d-block">السائق المرتبط:</label>
                                <span class="fw-bolder fs-6 text-gray-800">{{ $ticket->driver->name ?? '' }}</span>
                                <div class="text-muted fs-7">📱 {{ $ticket->driver->phone ?? '' }}</div>
                            </div>
                        @endif

                        @if($ticket->order)
                            <div class="mb-4">
                                <label class="text-muted fs-7 d-block">الرحلة المرتبطة:</label>
                                <a href="{{ route('admin.shipping-orders.index') }}" class="btn btn-sm btn-light-info fw-bold w-100 mt-2">
                                    <i class="ki-outline ki-truck fs-4 me-1"></i> عرض تفاصيل الطلب #{{ $ticket->order->id }}
                                </a>
                            </div>
                        @endif

                        <div class="mb-4">
                            <label class="text-muted fs-7 d-block">تاريخ التقديم:</label>
                            <span class="fw-bold text-gray-800">{{ $ticket->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
