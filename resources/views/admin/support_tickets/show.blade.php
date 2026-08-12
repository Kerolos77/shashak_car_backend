@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <!-- Top Header Card -->
        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between flex-wrap gap-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-message-text-2 fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">تذكرة الدعم والشكوى #{{ $ticket->ticket_number }}</h2>
                        <div class="text-muted fs-6">
                            العميل: <span class="fw-bold text-dark">{{ $ticket->user->name ?? 'غير محدد' }}</span> 
                            @if($ticket->driver)
                                | السائق: <span class="fw-bold text-dark">{{ $ticket->driver->name ?? 'غير محدد' }}</span>
                            @endif
                            @if($ticket->order_id)
                                | الرحلة: <span class="badge badge-light-primary fw-bolder">#{{ $ticket->order_id }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @php
                        $statusBadges = [
                            'open' => 'badge-light-danger',
                            'in_review' => 'badge-light-warning',
                            'resolved' => 'badge-light-success',
                            'closed' => 'badge-light-dark',
                        ];
                        $statusNames = [
                            'open' => 'مفتوحة (جديدة)',
                            'in_review' => 'قيد المراجعة والتحقيق',
                            'resolved' => 'تم الحل والتعويض',
                            'closed' => 'مغلقة',
                        ];
                    @endphp
                    <span class="badge {{ $statusBadges[$ticket->status] ?? 'badge-light-primary' }} fs-5 py-3 px-4 fw-bolder">
                        {{ $statusNames[$ticket->status] ?? $ticket->status }}
                    </span>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-light fw-bold">
                        <i class="ki-outline ki-arrow-right fs-4 me-1"></i> العودة للتذاكر
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center p-4 mb-6">
                <i class="ki-outline ki-check-circle fs-2x me-3 text-success"></i>
                <div class="fw-bold fs-6">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Nav Tabs Header -->
        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-5 fw-bold mb-6 gap-2" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-4 px-5 active border-0 rounded bg-white shadow-sm" data-bs-toggle="tab" href="#tab_ticket_details" role="tab">
                    <i class="ki-outline ki-shield-search fs-3 me-2"></i> تفاصيل الشكوى والرد
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-4 px-5 border-0 rounded bg-white shadow-sm" data-bs-toggle="tab" href="#tab_trip_details" role="tab">
                    <i class="ki-outline ki-truck fs-3 me-2"></i> تفاصيل الرحلة #{{ $ticket->order_id ?? '-' }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-4 px-5 border-0 rounded bg-white shadow-sm" data-bs-toggle="tab" href="#tab_live_location" role="tab" id="btn_tab_map">
                    <i class="ki-outline ki-geolocation fs-3 me-2"></i> خريطة وتتبع المباشر
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-4 px-5 border-0 rounded bg-white shadow-sm position-relative" data-bs-toggle="tab" href="#tab_chat_history" role="tab">
                    <i class="ki-outline ki-messages fs-3 me-2"></i> محادثات الشات
                    @if(isset($chatMessages) && $chatMessages->count() > 0)
                        <span class="badge badge-circle badge-primary ms-2">{{ $chatMessages->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-active-primary py-4 px-5 border-0 rounded bg-white shadow-sm" data-bs-toggle="tab" href="#tab_profiles" role="tab">
                    <i class="ki-outline ki-user-square fs-3 me-2"></i> بروفايل العميل والسائق
                </a>
            </li>
        </ul>

        <!-- Tab Content Panes -->
        <div class="tab-content">

            <!-- TAB 1: Ticket Info & Response Form -->
            <div class="tab-pane fade show active" id="tab_ticket_details" role="tabpanel">
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
                                        <h5 class="fw-bold text-gray-800">ملاحظات وردود الدعم الفني السابقة:</h5>
                                        <div class="p-4 bg-light-warning text-dark border border-warning rounded fs-6">
                                            {!! nl2br(e($ticket->admin_notes)) !!}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Update Form & Notification Control -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light py-4">
                                <h3 class="card-title fw-bolder mb-0 text-gray-800">تحديث حالة التذكرة والرد والإشعارات</h3>
                            </div>
                            <div class="card-body p-6">
                                <form action="{{ route('admin.tickets.update-status', $ticket->id) }}" method="POST">
                                    @csrf
                                    <div class="row g-4 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label required fw-bold">حالة التذكرة</label>
                                            <select name="status" class="form-select form-select-solid" required>
                                                <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>مفتوحة (جديدة)</option>
                                                <option value="in_review" {{ $ticket->status === 'in_review' ? 'selected' : '' }}>قيد المراجعة والتحقيق</option>
                                                <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>تم الحل والتعويض (Resolved)</option>
                                                <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>مغلقة (Closed)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">توجيه إشعار فوري (Push Notification)</label>
                                            <select name="notify_target" class="form-select form-select-solid">
                                                <option value="none">بدون إشعار</option>
                                                <option value="user" selected>إرسال إشعار للعميل فقط ({{ $ticket->user->name ?? 'العميل' }})</option>
                                                @if($ticket->driver)
                                                    <option value="driver">إرسال إشعار للسائق فقط ({{ $ticket->driver->name ?? 'السائق' }})</option>
                                                    <option value="both">إرسال إشعار للطرفين (العميل والسائق)</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="form-label fw-bold">إضافة رد / ملاحظات الدعم الفني والإدارة</label>
                                        <textarea name="admin_notes" class="form-control form-control-solid" rows="4" placeholder="اكتب رد الإدارة أو تفاصيل إجراء التعويض هنا...">{{ $ticket->admin_notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary fw-bold px-6 py-3">
                                        <i class="ki-outline ki-send fs-3 me-2"></i> حفظ وتحديث التذكرة وإرسال الإشعار
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 mb-6">
                            <div class="card-header bg-light py-4">
                                <h3 class="card-title fw-bolder mb-0 text-gray-800">ملخص التذكرة</h3>
                            </div>
                            <div class="card-body p-6">
                                <div class="mb-4">
                                    <label class="text-muted fs-7 d-block">العميل (مقدم الشكوى):</label>
                                    <span class="fw-bolder fs-6 text-gray-800">{{ $ticket->user->name ?? 'غير محدد' }}</span>
                                    <div class="text-muted fs-7">📱 {{ $ticket->user->phone_number ?? $ticket->user->phone ?? '-' }}</div>
                                </div>

                                @if($ticket->driver)
                                    <div class="mb-4">
                                        <label class="text-muted fs-7 d-block">السائق المشكو بحقه/المشترك:</label>
                                        <span class="fw-bolder fs-6 text-gray-800">{{ $ticket->driver->name ?? '' }}</span>
                                        <div class="text-muted fs-7">📱 {{ $ticket->driver->phone_number ?? $ticket->driver->phone ?? '-' }}</div>
                                    </div>
                                @endif

                                @if($ticket->order)
                                    <div class="mb-4">
                                        <label class="text-muted fs-7 d-block">رقم الرحلة:</label>
                                        <span class="badge badge-light-primary fs-6 fw-bold">#{{ $ticket->order->id }}</span>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="text-muted fs-7 d-block">تاريخ الإنشاء:</label>
                                    <span class="fw-bold text-gray-800">{{ $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i') : '-' }}</span>
                                </div>

                                @if($ticket->resolved_at)
                                    <div class="mb-4">
                                        <label class="text-muted fs-7 d-block">تاريخ الإغلاق/الحل:</label>
                                        <span class="fw-bold text-success">{{ $ticket->resolved_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Trip Details -->
            <div class="tab-pane fade" id="tab_trip_details" role="tabpanel">
                @if($ticket->order)
                    @php $order = $ticket->order; @endphp
                    <div class="card shadow-sm border-0 mb-6">
                        <div class="card-header bg-light py-4 d-flex align-items-center justify-content-between">
                            <h3 class="card-title fw-bolder mb-0 text-gray-800">بيانات وتفاصيل الرحلة #{{ $order->id }}</h3>
                            <span class="badge badge-light-info fs-6 fw-bold">{{ $order->status }}</span>
                        </div>
                        <div class="card-body p-6">
                            <div class="row g-6 mb-6">
                                <div class="col-md-4">
                                    <div class="p-4 bg-light rounded">
                                        <div class="text-muted fs-7 mb-1">نوع الخدمة</div>
                                        <div class="fw-bolder fs-5 text-primary">{{ $order->service->name ?? 'خدمة توصيل' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 bg-light rounded">
                                        <div class="text-muted fs-7 mb-1">إجمالي التكلفة / السعر النهائي</div>
                                        <div class="fw-bolder fs-4 text-success">{{ number_format($order->final_rate, 2) }} ج.م</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 bg-light rounded">
                                        <div class="text-muted fs-7 mb-1">المسافة المقدرة</div>
                                        <div class="fw-bolder fs-5 text-dark">{{ $order->distance ? number_format($order->distance, 2) . ' كم' : 'غير محدد' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-6 mb-6">
                                <div class="col-md-6">
                                    <div class="border rounded p-4">
                                        <h5 class="fw-bold text-success mb-3"><i class="ki-outline ki-geolocation text-success fs-3 me-1"></i> نقطة التحرك والانطلاق (Pickup)</h5>
                                        <div class="fw-bold text-gray-800 fs-6">{{ $order->start_address ?? 'غير مسجل' }}</div>
                                        @if($order->start_lat && $order->start_long)
                                            <div class="text-muted fs-7 mt-1">الإحداثيات: {{ $order->start_lat }}, {{ $order->start_long }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-4">
                                        <h5 class="fw-bold text-danger mb-3"><i class="ki-outline ki-map-pin text-danger fs-3 me-1"></i> نقطة الوصول والنهاية (Destination)</h5>
                                        <div class="fw-bold text-gray-800 fs-6">{{ $order->end_address ?? 'غير مسجل' }}</div>
                                        @if($order->end_lat && $order->end_long)
                                            <div class="text-muted fs-7 mt-1">الإحداثيات: {{ $order->end_lat }}, {{ $order->end_long }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Breakdown -->
                            <div class="card bg-light border-0 p-5 mb-6">
                                <h5 class="fw-bold mb-3">تفاصيل وطريقة الدفع:</h5>
                                <div class="row g-4 text-center">
                                    <div class="col-md-3">
                                        <div class="text-muted fs-7">حالة الدفع</div>
                                        <div class="fw-bolder fs-6 text-primary">{{ $order->payment_status ?? 'pending' }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted fs-7">مدفوع محفظة</div>
                                        <div class="fw-bolder fs-6 text-dark">{{ number_format($order->wallet_paid ?? 0, 2) }} ج.م</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted fs-7">مدفوع كارت/فيزا</div>
                                        <div class="fw-bolder fs-6 text-dark">{{ number_format($order->card_paid ?? 0, 2) }} ج.م</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted fs-7">مدفوع كاش</div>
                                        <div class="fw-bolder fs-6 text-dark">{{ number_format($order->cash_paid ?? 0, 2) }} ج.م</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps Timeline -->
                            <h5 class="fw-bold mb-3">سجل أوقات الرحلة:</h5>
                            <div class="row g-3">
                                <div class="col-md-3"><span class="text-muted fs-7 d-block">تم قبول الطلب:</span> <span class="fw-bold">{{ $order->assigned_at ? $order->assigned_at->format('Y-m-d H:i') : '-' }}</span></div>
                                <div class="col-md-3"><span class="text-muted fs-7 d-block">وصول السائق:</span> <span class="fw-bold">{{ $order->arrived_at ? $order->arrived_at->format('Y-m-d H:i') : '-' }}</span></div>
                                <div class="col-md-3"><span class="text-muted fs-7 d-block">بدء الرحلة:</span> <span class="fw-bold">{{ $order->on_trip_at ? $order->on_trip_at->format('Y-m-d H:i') : '-' }}</span></div>
                                <div class="col-md-3"><span class="text-muted fs-7 d-block">إكتمال الرحلة:</span> <span class="fw-bold">{{ $order->completed_at ? $order->completed_at->format('Y-m-d H:i') : '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning p-6 text-center">
                        <i class="ki-outline ki-information fs-2x mb-2 text-warning d-block"></i>
                        <h4 class="fw-bold">لا يوجد رحلة مرتبطة بهذه التذكرة</h4>
                        <div class="text-muted">هذه التذكرة عبارة عن استفسار أو شكوى عامة غير مرتبطة برقم طلب محدد.</div>
                    </div>
                @endif
            </div>

            <!-- TAB 3: Live Location & Map -->
            <div class="tab-pane fade" id="tab_live_location" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-4 d-flex align-items-center justify-content-between">
                        <h3 class="card-title fw-bolder mb-0 text-gray-800">تتبع الموقع المباشر وخريطة المسار</h3>
                        <span class="text-muted fs-7"><i class="ki-outline ki-geolocation text-primary me-1"></i> يتم العرض عبر الخريطة التفاعلية</span>
                    </div>
                    <div class="card-body p-6">
                        @if($ticket->order && ($ticket->order->start_lat || $ticket->order->end_lat || ($ticket->driver && $ticket->driver->lat)))
                            <div id="ticket_map" style="height: 480px; width: 100%; border-radius: 8px;" class="border shadow-sm"></div>

                            <div class="row g-4 mt-4">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <span class="bullet bullet-vertical bg-success h-40px me-3"></span>
                                        <div>
                                            <div class="text-muted fs-7">موقع الانطلاق (Pickup)</div>
                                            <div class="fw-bold fs-7 text-dark">{{ $ticket->order->start_address ?? 'مسجل بالإحداثيات' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <span class="bullet bullet-vertical bg-danger h-40px me-3"></span>
                                        <div>
                                            <div class="text-muted fs-7">موقع الوصول (Destination)</div>
                                            <div class="fw-bold fs-7 text-dark">{{ $ticket->order->end_address ?? 'مسجل بالإحداثيات' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <span class="bullet bullet-vertical bg-primary h-40px me-3"></span>
                                        <div>
                                            <div class="text-muted fs-7">الموقع المباشر للسائق</div>
                                            <div class="fw-bold fs-7 text-dark">
                                                {{ $ticket->driver->name ?? 'السائق' }}: 
                                                {{ $ticket->driver->lat ?? $ticket->order->start_lat ?? 'جاري التحديد...' }}, 
                                                {{ $ticket->driver->long ?? $ticket->order->start_long ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning p-6 text-center">
                                <i class="ki-outline ki-map-pin fs-2x mb-2 text-warning d-block"></i>
                                <h4 class="fw-bold">لا تتوفر إحداثيات موقع لهذه الرحلة أو الشكوى</h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 4: Driver & User Chat -->
            <div class="tab-pane fade" id="tab_chat_history" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light py-4">
                        <h3 class="card-title fw-bolder mb-0 text-gray-800">سجل محادثة الشات المباشرة بين السائق والعميل</h3>
                    </div>
                    <div class="card-body p-6">
                        @if(isset($chatMessages) && $chatMessages->count() > 0)
                            <div class="scroll-y me-n5 pe-5 max-h-500px" style="max-height: 500px; overflow-y: auto;">
                                @foreach($chatMessages as $msg)
                                    @php
                                        $isUser = ($msg->sender_id == $ticket->user_id);
                                    @endphp
                                    <div class="d-flex justify-content-{{ $isUser ? 'start' : 'end' }} mb-6">
                                        <div class="d-flex flex-column align-items-{{ $isUser ? 'start' : 'end' }}">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="fw-bold text-gray-800 fs-7 me-2">{{ $msg->sender->name ?? ($isUser ? 'العميل' : 'السائق') }}</span>
                                                <span class="text-muted fs-8">{{ $msg->created_at ? $msg->created_at->format('H:i - Y/m/d') : '' }}</span>
                                            </div>
                                            <div class="p-4 rounded {{ $isUser ? 'bg-light-primary text-dark' : 'bg-light-success text-dark' }} fw-semibold fs-6 max-w-400px border" style="max-width: 450px;">
                                                @if($msg->message)
                                                    <p class="mb-1">{!! nl2br(e($msg->message)) !!}</p>
                                                @endif
                                                @if($msg->image)
                                                    <div class="mt-2">
                                                        <a href="{{ asset($msg->image) }}" target="_blank">
                                                            <img src="{{ asset($msg->image) }}" class="rounded mw-100 max-h-200px" style="max-height: 180px;" alt="صورة شات" />
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light-info p-6 text-center border border-info">
                                <i class="ki-outline ki-message-text fs-2x text-info mb-2 d-block"></i>
                                <h4 class="fw-bold">لا يوجد رسائل شات مسجلة لهذه الرحلة</h4>
                                <div class="text-muted fs-7">لم يتم إجراء محادثات نصية بين العميل والسائق خلال وقت هذه الرحلة.</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- TAB 5: Profiles -->
            <div class="tab-pane fade" id="tab_profiles" role="tabpanel">
                <div class="row g-6">
                    <!-- Client Profile Card -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light-primary py-4">
                                <h3 class="card-title fw-bolder mb-0 text-primary"><i class="ki-outline ki-user fs-3 me-2"></i> البيانات الكاملة للعميل</h3>
                            </div>
                            <div class="card-body p-6">
                                @if($ticket->user)
                                    @php $u = $ticket->user; @endphp
                                    <div class="d-flex align-items-center mb-6">
                                        <div class="symbol symbol-65px symbol-circle me-4 border">
                                            <img src="{{ $u->profile_pic ? asset($u->profile_pic) : asset('assets/media/avatars/blank.png') }}" alt="{{ $u->name }}" />
                                        </div>
                                        <div>
                                            <h4 class="fw-bolder mb-1 text-gray-800">{{ $u->name }}</h4>
                                            <div class="text-muted fs-7">📱 {{ $u->phone_number ?? $u->phone ?? '-' }}</div>
                                            <div class="text-muted fs-7">✉️ {{ $u->email ?? 'لا يوجد بريد إلكتروني' }}</div>
                                        </div>
                                    </div>

                                    <div class="separator mb-6"></div>

                                    <div class="row g-4">
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <span class="text-muted fs-7 d-block">رصيد المحفظة:</span>
                                                <span class="fw-bolder fs-5 text-success">{{ number_format($u->wallet_amount ?? 0, 2) }} ج.م</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <span class="text-muted fs-7 d-block">تقييم العميل:</span>
                                                <span class="fw-bolder fs-5 text-warning">⭐ {{ number_format($u->rate ?? 5.0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-light rounded">
                                                <span class="text-muted fs-7 d-block">تاريخ التسجيل:</span>
                                                <span class="fw-bold text-gray-800">{{ $u->created_at ? $u->created_at : '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-muted">بيانات العميل غير متوفرة</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Driver Profile Card -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-light-success py-4">
                                <h3 class="card-title fw-bolder mb-0 text-success"><i class="ki-outline ki-steering-wheel fs-3 me-2"></i> البيانات الكاملة للسائق والسيارة</h3>
                            </div>
                            <div class="card-body p-6">
                                @if($ticket->driver)
                                    @php
                                        $d = $ticket->driver;
                                        $car = $d->profile?->driverCar ?? null;
                                    @endphp
                                    <div class="d-flex align-items-center mb-6">
                                        <div class="symbol symbol-65px symbol-circle me-4 border">
                                            <img src="{{ $d->profile_pic ? asset($d->profile_pic) : asset('assets/media/avatars/blank.png') }}" alt="{{ $d->name }}" />
                                        </div>
                                        <div>
                                            <h4 class="fw-bolder mb-1 text-gray-800">{{ $d->name }}</h4>
                                            <div class="text-muted fs-7">📱 {{ $d->phone_number ?? $d->phone ?? '-' }}</div>
                                            <div class="text-muted fs-7">✉️ {{ $d->email ?? 'لا يوجد بريد إلكتروني' }}</div>
                                        </div>
                                    </div>

                                    <div class="separator mb-6"></div>

                                    <div class="row g-4 mb-4">
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <span class="text-muted fs-7 d-block">رصيد المحفظة:</span>
                                                <span class="fw-bolder fs-5 text-success">{{ number_format($d->wallet_amount ?? 0, 2) }} ج.م</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-3 bg-light rounded">
                                                <span class="text-muted fs-7 d-block">تقييم السائق:</span>
                                                <span class="fw-bolder fs-5 text-warning">⭐ {{ number_format($d->rate ?? 5.0, 1) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($car)
                                        <h5 class="fw-bold mb-3 text-dark"><i class="ki-outline ki-car fs-4 me-1"></i> بيانات مركبة السائق:</h5>
                                        <div class="p-4 bg-light border rounded">
                                            <div class="row g-2">
                                                <div class="col-6"><span class="text-muted fs-7">الماركة:</span> <span class="fw-bold">{{ $car->brand->name ?? 'غير مسجل' }}</span></div>
                                                <div class="col-6"><span class="text-muted fs-7">الموديل:</span> <span class="fw-bold">{{ $car->model->name ?? 'غير مسجل' }}</span></div>
                                                <div class="col-6"><span class="text-muted fs-7">اللون:</span> <span class="fw-bold">{{ $car->color ?? 'غير مسجل' }}</span></div>
                                                <div class="col-6"><span class="text-muted fs-7">رقم اللوحة:</span> <span class="badge badge-light-primary fw-bolder fs-7">{{ $car->plate_number ?? '-' }}</span></div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-light p-4 text-muted text-center">
                                        لا يوجد سائق مرتبط بهذه التذكرة.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Leaflet Map CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var mapInitialized = false;
    var map = null;

    function initTicketMap() {
        if (mapInitialized) return;

        var startLat = {{ $ticket->order->start_lat ?? 30.0444 }};
        var startLng = {{ $ticket->order->start_long ?? 31.2357 }};
        var endLat   = {{ $ticket->order->end_lat ?? 30.0444 }};
        var endLng   = {{ $ticket->order->end_long ?? 31.2357 }};
        var driverLat = {{ $ticket->driver->lat ?? $ticket->order->start_lat ?? 30.0444 }};
        var driverLng = {{ $ticket->driver->long ?? $ticket->order->start_long ?? 31.2357 }};

        var mapContainer = document.getElementById('ticket_map');
        if (!mapContainer) return;

        map = L.map('ticket_map').setView([startLat, startLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var bounds = [];

        // Pickup Marker
        if (startLat && startLng) {
            L.marker([startLat, startLng]).addTo(map)
                .bindPopup('<b>📍 نقطة التحرك (Pickup)</b><br>{{ e($ticket->order->start_address ?? "") }}').openPopup();
            bounds.push([startLat, startLng]);
        }

        // Destination Marker
        if (endLat && endLng && (endLat !== startLat || endLng !== startLng)) {
            L.marker([endLat, endLng]).addTo(map)
                .bindPopup('<b>🏁 نقطة الوصول (Destination)</b><br>{{ e($ticket->order->end_address ?? "") }}');
            bounds.push([endLat, endLng]);
        }

        // Driver Live Marker
        if (driverLat && driverLng && (driverLat !== startLat || driverLng !== startLng)) {
            L.circleMarker([driverLat, driverLng], {
                color: '#3699FF',
                fillColor: '#3699FF',
                fillOpacity: 0.8,
                radius: 10
            }).addTo(map).bindPopup('<b>🚗 الموقع المباشر للسائق</b><br>{{ e($ticket->driver->name ?? "السائق") }}');
            bounds.push([driverLat, driverLng]);
        }

        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [40, 40] });
        }

        mapInitialized = true;
    }

    // Trigger map load when map tab is clicked
    var mapTabBtn = document.getElementById('btn_tab_map');
    if (mapTabBtn) {
        mapTabBtn.addEventListener('shown.bs.tab', function () {
            setTimeout(function() {
                initTicketMap();
                if (map) {
                    map.invalidateSize();
                }
            }, 200);
        });
    }
});
</script>
@endsection
