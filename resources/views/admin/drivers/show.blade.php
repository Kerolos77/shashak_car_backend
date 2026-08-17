@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ __('admin.drivers') ?? 'Ø§Ù„Ø³Ø§Ø¦Ù‚ÙˆÙ†' }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ __('admin.view') ?? 'Ø¹Ø±Ø¶ Ø§Ù„ØªÙØ§ØµÙŠÙ„' }}</li>
<!-- Modal: Reject Driver Registration -->
<div class="modal fade" id="rejectDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.drivers.reject', $row->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold text-white"><i class="ki-outline ki-cross-circle text-white me-2"></i>رفض طلب تسجيل السائق</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center p-3 mb-4">
                    <i class="ki-outline ki-information-5 fs-2x me-3 text-warning"></i>
                    <div class="fs-7 text-gray-800">
                        سيظهر سبب الرفض المكتوب هنا مباشرة في تطبيق الموبايل لدى السائق ليقوم بتصحيح المستندات وإعادة الإرسال.
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">سبب الرفض الموجه للسائق <span class="text-danger">*</span></label>
                    <textarea name="reason" rows="4" class="form-control" placeholder="مثال: صورة رخصة القيادة غير واضحة، يرجى إعادة رفع صورة واضحة للوجهين." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-danger fw-bold">تأكيد رفض الطلب</button>
            </div>
        </form>
    </div>
</div>

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
                            â­ VIP
                        </span>
                    @endif
                </div>
                
                <h3 class="text-gray-900 fw-bold fs-3 mb-1">
                    {{ $row->user->full_name }}
                    @if($row->user->is_vip)
                        <span class="badge badge-light-warning text-dark fw-bold ms-1" title="Ø³Ø§Ø¦Ù‚ Ù…Ù…ÙŠØ² (VIP)">â­ VIP</span>
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
                        <span class="badge bg-danger text-white fs-7 py-2 px-3" title="Ù…Ø­Ø¸ÙˆØ± Ù…Ù† Ø§Ù„ÙƒØ§Ø´ Ù…Ø¤Ù‚ØªØ§Ù‹">
                            <i class="ki-outline ki-lock text-white me-1"></i> Ù…Ø­Ø¸ÙˆØ± ÙƒØ§Ø´ ({{ ceil($row->user->cash_restriction_seconds_remaining / 60) }} Ø¯Ù‚ÙŠÙ‚Ø© Ù…ØªØ¨Ù‚ÙŠØ©)
                        </span>
                    @else
                        <span class="badge bg-light-success text-success fs-7 py-2 px-3">
                            <i class="ki-outline ki-check text-success me-1"></i> Ø§Ù„ÙƒØ§Ø´ Ù…ØªØ§Ø­
                        </span>
                    @endif
                </div>

                <div class="separator separator-dashed my-4"></div>

                <!-- Admin Control Actions -->
                <div class="d-flex flex-column gap-2 text-start">
                    <span class="text-muted fw-bold fs-8 uppercase tracking-wider mb-1">Ø¥Ø¬Ø±Ø§Ø¡Ø§Øª Ù„ÙˆØ­Ø© Ø§Ù„ØªØ­ÙƒÙ… Ø§Ù„Ø³Ø±ÙŠØ¹Ø©</span>
                    
                    <div class="d-flex gap-2">
                        <!-- Block / Unblock General -->
                        @if($row->status == 'blocked')
                            <form action="{{ route('admin.drivers.active', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success w-100 py-2">
                                    <i class="ki-outline ki-check-circle fs-5 me-1"></i> ÙÙƒ Ø§Ù„Ø­Ø¸Ø± Ø§Ù„Ø¹Ø§Ù…
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.drivers.block', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-2" onclick="return confirm('Ù‡Ù„ Ø£Ù†Øª ØªØ£ÙƒØ¯ Ù…Ù† Ø­Ø¸Ø± Ø§Ù„Ø³Ø§Ø¦Ù‚ØŸ')">
                                    <i class="ki-outline ki-ban fs-5 me-1"></i> Ø­Ø¸Ø± Ø§Ù„Ø³Ø§Ø¦Ù‚
                                </button>
                            </form>
                        @endif

                        <!-- Reset Cash Ban -->
                        @if(($row->user->cash_restriction_seconds_remaining ?? 0) > 0)
                            <form action="{{ route('admin.drivers.reset-cash-ban', $row->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning text-dark w-100 py-2" title="ØªØµÙÙŠØ± ÙˆÙÙƒ Ø­Ø¸Ø± Ø§Ù„ÙƒØ§Ø´ ÙÙˆØ±Ø§Ù‹">
                                    <i class="ki-outline ki-key fs-5 me-1"></i> ÙÙƒ Ø­Ø¸Ø± Ø§Ù„ÙƒØ§Ø´
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="d-flex gap-2 mt-1">
                        <!-- Toggle VIP -->
                        <form action="{{ route('admin.drivers.toggle-vip', $row->id) }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $row->user->is_vip ? 'btn-light-warning' : 'btn-outline-warning' }} w-100 py-2">
                                <i class="ki-outline ki-star fs-5 me-1"></i> {{ $row->user->is_vip ? 'Ø¥Ù„ØºØ§Ø¡ VIP' : 'ØªÙ…Ø¨ÙŠØ² ÙƒÙ€ VIP â­' }}
                            </button>
                        </form>

                        <!-- Add Wallet Money Modal Button -->
                        <button type="button" class="btn btn-sm btn-primary w-100 py-2" data-bs-toggle="modal" data-bs-target="#addWalletModal">
                            <i class="ki-outline ki-wallet fs-5 me-1"></i> Ø¥Ø¶Ø§ÙØ© Ø±ØµÙŠØ¯
                        </button>
                    </div>

                    <div class="d-flex gap-2 mt-1">
                        <!-- Gift Package Modal Button -->
                        <button type="button" class="btn btn-sm btn-info text-white w-100 py-2" data-bs-toggle="modal" data-bs-target="#giftPackageModal">
                            <i class="ki-outline ki-gift fs-5 me-1"></i> Ø¥Ù‡Ø¯Ø§Ø¡ Ø¨Ø§Ù‚Ø©
                        </button>
                        
                        <a href="{{ route('admin.drivers.edit', $row->id) }}" class="btn btn-sm btn-light w-100 py-2">
                            <i class="ki-outline ki-pencil fs-5 me-1"></i> {{ trans('global.edit') }}
                        </a>
                    </div>
                </div>

                <div class="separator separator-dashed my-4"></div>

                <div class="d-grid gap-2">
                    <a href="{{ route('admin.drivers.export-pdf', $row->id) }}" class="btn btn-sm btn-danger py-2" target="_blank">
                        <i class="ki-outline ki-document fs-5 me-1"></i> ØªØµØ¯ÙŠØ± Ù…Ù„Ù Ø£Ù…Ù†ÙŠ Ø±Ø³Ù…ÙŠ (PDF)
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
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø±Ø­Ù„Ø§Øª</span>
                    <span class="fs-2x fw-bold text-primary">{{ $orderStats['total'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-success border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">Ø§Ù„Ø±Ø­Ù„Ø§Øª Ø§Ù„Ù…ÙƒØªÙ…Ù„Ø©</span>
                    <span class="fs-2x fw-bold text-success">{{ $orderStats['completed'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-danger border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">Ø§Ù„Ø±Ø­Ù„Ø§Øª Ø§Ù„Ù…Ù„ØºØ§Ø©</span>
                    <span class="fs-2x fw-bold text-danger">{{ $orderStats['canceled'] }}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card bg-light-info border-0 p-4 rounded-3 text-center">
                    <span class="fs-6 text-muted fw-semibold d-block mb-1">Ø¥Ø¬Ù…Ø§Ù„ÙŠ Ø§Ù„Ø£Ø±Ø¨Ø§Ø­</span>
                    <span class="fs-2x fw-bold text-info">{{ number_format($orderStats['total_earnings'], 0) }}Ø¬.Ù…</span>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6 fw-bold">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tab_driver_info">
                    <i class="ki-outline ki-user fs-4 me-1"></i> Ù…Ø¹Ù„ÙˆÙ…Ø§Øª Ø§Ù„Ø³Ø§Ø¦Ù‚ ÙˆØ§Ù„Ø£ÙˆØ±Ø§Ù‚
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_reviews">
                    <i class="ki-outline ki-star fs-4 me-1"></i> Ø§Ù„ØªÙ‚ÙŠÙŠÙ…Ø§Øª ÙˆØ§Ù„Ù…Ø±Ø§Ø¬Ø¹Ø§Øª ({{ $reviews->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_packages">
                    <i class="ki-outline ki-box fs-4 me-1"></i> Ø§Ù„Ø¨Ø§Ù‚Ø§Øª ÙˆØ§Ù„Ø§Ø´ØªØ±Ø§ÙƒØ§Øª ({{ $activePackages->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_driver_audit">
                    <i class="ki-outline ki-shield-check fs-4 me-1"></i> Ø³Ø¬Ù„ Ø¥Ø¬Ø±Ø§Ø¡Ø§Øª Ø§Ù„Ø¥Ø¯Ø§Ø±Ø© ({{ $auditLogs->count() }})
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
                                        <th class="fw-bold text-gray-500">Ù…ØªÙˆØ³Ø· Ø§Ù„ØªÙ‚ÙŠÙŠÙ… Ø§Ù„Ø¹Ø§Ù…</th>
                                        <td class="fw-bold text-gray-900">
                                            â­ {{ number_format($row->user->rating ?? 5.0, 2) }} / 5.0
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
                        <h4 class="fw-bold text-gray-900 mb-0">ØªÙ‚ÙŠÙŠÙ…Ø§Øª ÙˆÙ…Ø±Ø§Ø¬Ø¹Ø§Øª Ø§Ù„Ø±ÙƒØ§Ø¨ Ø§Ù„Ø³Ø§Ø¨Ù‚Ø©</h4>
                        <span class="badge bg-light-primary text-primary fw-bold">Ø§Ù„Ø¹Ø¯Ø¯: {{ $reviews->count() }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Ø§Ù„Ø±Ø§ÙƒØ¨</th>
                                    <th>Ø§Ù„ØªÙ‚ÙŠÙŠÙ…</th>
                                    <th>Ø§Ù„ØªØ¹Ù„ÙŠÙ‚</th>
                                    <th>Ø§Ù„ØªØ§Ø±ÙŠØ®</th>
                                    <th>Ø¥Ø¬Ø±Ø§Ø¡</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $rev)
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            {{ $rev->fromUser->full_name ?? $rev->fromUser->name ?? 'Ø±Ø§ÙƒØ¨ #' . $rev->from_user_id }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light-warning text-dark fw-bold">â­ {{ $rev->rating }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            {{ $rev->comment ?? 'Ù„Ø§ ÙŠÙˆØ¬Ø¯ ØªØ¹Ù„ÙŠÙ‚' }}
                                        </td>
                                        <td class="text-muted small">
                                            {{ $rev->created_at ? $rev->created_at->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.reviews.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Ù‡Ù„ Ø£Ù†Øª Ù…Ù…ØªØ£ÙƒØ¯ Ù…Ù† Ø­Ø°Ù Ù‡Ø°Ø§ Ø§Ù„ØªÙ‚ÙŠÙŠÙ…ØŸ Ø³ÙŠØªÙ… Ø¥Ø¹Ø§Ø¯Ø© Ø­Ø³Ø§Ø¨ Ù…ØªÙˆØ³Ø· ØªÙ‚ÙŠÙŠÙ… Ø§Ù„Ø³Ø§Ø¦Ù‚ ØªÙ„Ù‚Ø§Ø¦ÙŠØ§Ù‹.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Ø­Ø°Ù Ø§Ù„ØªÙ‚ÙŠÙŠÙ… Ø§Ù„ÙƒÙŠØ¯ÙŠ">
                                                    <i class="ki-outline ki-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Ù„Ø§ ØªÙˆØ¬Ø¯ ØªÙ‚ÙŠÙŠÙ…Ø§Øª Ù…ÙƒØªÙˆØ¨Ø© Ù„Ù‡Ø°Ø§ Ø§Ù„Ø³Ø§Ø¦Ù‚ Ø­ØªÙ‰ Ø§Ù„Ø¢Ù†.</td>
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
                        <h4 class="fw-bold text-gray-900 mb-0">Ø§Ù„Ø¨Ø§Ù‚Ø§Øª ÙˆØ§Ù„Ø§Ø´ØªØ±Ø§ÙƒØ§Øª Ø§Ù„Ø­Ø§Ù„ÙŠØ©</h4>
                        <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#giftPackageModal">
                            <i class="ki-outline ki-gift me-1"></i> Ø¥Ù‡Ø¯Ø§Ø¡ Ø¨Ø§Ù‚Ø© Ø¬Ø¯ÙŠØ¯Ø©
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Ø§Ø³Ù… Ø§Ù„Ø¨Ø§Ù‚Ø©</th>
                                    <th>ØªØ§Ø±ÙŠØ® Ø§Ù„Ø§Ù†ØªÙ‡Ø§Ø¡</th>
                                    <th>Ø§Ù„Ø­Ø§Ù„Ø©</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activePackages as $purch)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $purch->package->name ?? 'Ø¨Ø§Ù‚Ø© Ù…Ø®ØµØµØ©' }}</td>
                                        <td>{{ $purch->expires_at ? $purch->expires_at->format('Y-m-d') : '-' }}</td>
                                        <td>
                                            @if($purch->expires_at && $purch->expires_at->isFuture())
                                                <span class="badge bg-success">Ù†Ø´Ø·Ø©</span>
                                            @else
                                                <span class="badge bg-secondary">Ù…Ù†ØªÙ‡ÙŠØ©</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Ø§Ù„Ø³Ø§Ø¦Ù‚ ØºÙŠØ± Ù…Ø´ØªØ±Ùƒ ÙÙŠ Ø£ÙŠ Ø¨Ø§Ù‚Ø§Øª Ø­Ø§Ù„ÙŠØ§Ù‹.</td>
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
                        <h4 class="fw-bold text-gray-900 mb-1">Ø³Ø¬Ù„ Ø¥Ø¬Ø±Ø§Ø¡Ø§Øª Ø§Ù„Ø¥Ø¯Ø§Ø±Ø© Ø¹Ù„Ù‰ Ø§Ù„Ø­Ø³Ø§Ø¨</h4>
                        <p class="text-muted small mb-0">Ø³Ø¬Ù„ Ø´ÙØ§Ù Ø¨Ø¬Ù…ÙŠØ¹ Ø§Ù„ØªØ¹Ø¯ÙŠÙ„Ø§Øª ÙˆØ§Ù„Ù…Ø¹Ø§Ù…Ù„Ø§Øª Ø§Ù„ØªÙŠ Ù‚Ø§Ù… Ø¨Ù‡Ø§ Ø§Ù„Ø¢Ø¯Ù…ÙŠÙ† Ù„Ø¶Ù…Ø§Ù† Ø§Ù„Ø¯Ù‚Ø© ÙˆØ§Ù„Ø±Ù‚Ø§Ø¨Ø©.</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Ø§Ù„Ù…Ø´Ø±Ù / Admin</th>
                                    <th>Ø§Ù„Ø¥Ø¬Ø±Ø§Ø¡</th>
                                    <th>Ø§Ù„ØªÙØ§ØµÙŠÙ„ ÙˆØ§Ù„Ù…Ù„Ø§Ø­Ø¸Ø§Øª</th>
                                    <th>Ø§Ù„ØªØ§Ø±ÙŠØ® ÙˆØ§Ù„ÙˆÙ‚Øª</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($auditLogs as $log)
                                    <tr>
                                        <td class="fw-bold text-dark">
                                            <i class="ki-outline ki-user text-primary me-1"></i>
                                            {{ $log->admin->name ?? $log->admin->email ?? 'Ù…Ø´Ø±Ù Ø§Ù„Ù†Ø¸Ø§Ù…' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary fw-bold">{{ $log->action }}</span>
                                        </td>
                                        <td class="text-dark small">{{ $log->notes }}</td>
                                        <td class="text-muted small">{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Ù„Ø§ ØªÙˆØ¬Ø¯ Ø³Ø¬Ù„Ø§Øª Ø³Ø§Ø¨Ù‚Ø© Ù„Ù„Ù…Ø´Ø±ÙÙŠÙ† Ø¹Ù„Ù‰ Ù‡Ø°Ø§ Ø§Ù„Ø­Ø³Ø§Ø¨.</td>
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
                <h5 class="modal-title fw-bold">Ø¥Ø¶Ø§ÙØ© / Ø®ØµÙ… Ø±ØµÙŠØ¯ ÙÙŠ Ù…Ø­ÙØ¸Ø© Ø§Ù„Ø³Ø§Ø¦Ù‚</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Ø§Ù„Ù…Ø¨Ù„Øº (Ø¬Ù†ÙŠØ© Ù…ØµØ±ÙŠ)</label>
                    <input type="number" step="0.5" name="amount" class="form-control" placeholder="Ø£Ø¯Ø®Ù„ Ø§Ù„Ù…Ø¨Ù„Øº (Ø§Ø³ØªØ®Ø¯Ù… Ø³Ø§Ù„Ø¨ - Ù„Ø®ØµÙ… Ø±ØµÙŠØ¯)" required>
                    <small class="text-muted">Ø£Ø¯Ø®Ù„ 100 Ù„Ù„Ø¥Ø¶Ø§ÙØ© Ø£Ùˆ -50 Ù„Ù„Ø®ØµÙ… Ù…Ù† Ø§Ù„Ù…Ø­ÙØ¸Ø©.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ø³Ø¨Ø¨/Ù…Ù„Ø§Ø­Ø¸Ø© Ø§Ù„Ø¥Ø¶Ø§ÙØ©</label>
                    <input type="text" name="notes" class="form-control" placeholder="Ù…Ø«Ø§Ù„: Ù…ÙƒØ§ÙØ£Ø© ØªÙ…ÙŠØ² / ØªØ³ÙˆÙŠØ© Ø´Ø­Ù†">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Ø¥Ù„ØºØ§Ø¡</button>
                <button type="submit" class="btn btn-primary fw-bold">Ø­ÙØ¸ ÙˆØªØ¹Ø¯ÙŠÙ„ Ø§Ù„Ø±ØµÙŠØ¯</button>
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
                <h5 class="modal-title fw-bold">Ø¥Ù‡Ø¯Ø§Ø¡ Ø¨Ø§Ù‚Ø© Ù…Ø¬Ø§Ù†ÙŠØ© Ù„Ù„Ø³Ø§Ø¦Ù‚</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Ø§Ø®ØªØ± Ø§Ù„Ø¨Ø§Ù‚Ø© Ø§Ù„Ù…Ø±Ø§Ø¯ Ø¥Ù‡Ø¯Ø§Ø¦Ù‡Ø§</label>
                    <select name="package_id" class="form-select" required>
                        <option value="">-- Ø§Ø®ØªØ± Ø¨Ø§Ù‚Ø© Ù…Ù† Ø§Ù„Ø¨Ø§Ù‚Ø§Øª Ø§Ù„Ù…ØªØ§Ø­Ø© --</option>
                        @foreach($availablePackages as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->duration_days ?? 30 }} ÙŠÙˆÙ…)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Ø¥Ù„ØºØ§Ø¡</button>
                <button type="submit" class="btn btn-info text-white fw-bold">ØªÙØ¹ÙŠÙ„ ÙˆØªØ·Ø¨ÙŠÙ‚ Ø§Ù„Ø¨Ø§Ù‚Ø© Ø§Ù„Ù‡Ø¯ÙŠØ©</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Reject Driver Registration -->
<div class="modal fade" id="rejectDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.drivers.reject', $row->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold text-white"><i class="ki-outline ki-cross-circle text-white me-2"></i>رفض طلب تسجيل السائق</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center p-3 mb-4">
                    <i class="ki-outline ki-information-5 fs-2x me-3 text-warning"></i>
                    <div class="fs-7 text-gray-800">
                        سيظهر سبب الرفض المكتوب هنا مباشرة في تطبيق الموبايل لدى السائق ليقوم بتصحيح المستندات وإعادة الإرسال.
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">سبب الرفض الموجه للسائق <span class="text-danger">*</span></label>
                    <textarea name="reason" rows="4" class="form-control" placeholder="مثال: صورة رخصة القيادة غير واضحة، يرجى إعادة رفع صورة واضحة للوجهين." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-danger fw-bold">تأكيد رفض الطلب</button>
            </div>
        </form>
    </div>
</div>

@endsection

