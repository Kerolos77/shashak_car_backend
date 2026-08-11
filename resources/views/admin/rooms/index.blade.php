@extends('layouts.admin')

@section('title', __('Chats'))
@section('pageName', __('Chat Management'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">المحادثات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">عرض الكل</li>
@endsection

@section('content')
    @push('styles')
        <style>
            /* ===== Page Layout ===== */
            .chats-page-wrapper {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            /* ===== Stats Row ===== */
            .stats-row {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
            }

            .stat-card {
                background: #fff;
                border-radius: 1rem;
                padding: 1.25rem 1.5rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.06);
                border: 1px solid #f0f0f0;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            }

            .stat-icon {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .stat-icon.blue   { background: rgba(0, 112, 240, 0.1); color: #0070f0; }
            .stat-icon.green  { background: rgba(18, 183, 106, 0.1); color: #12b76a; }
            .stat-icon.orange { background: rgba(247, 144, 9, 0.1); color: #f79009; }

            .stat-info h4 {
                font-size: 1.6rem;
                font-weight: 700;
                margin: 0;
                color: #1e2334;
                line-height: 1;
            }

            .stat-info span {
                font-size: 0.8rem;
                color: #6b7280;
                margin-top: 0.2rem;
                display: block;
            }

            /* ===== Search & Filters Bar ===== */
            .search-bar-card {
                background: #fff;
                border-radius: 1rem;
                padding: 1.25rem 1.5rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.06);
                border: 1px solid #f0f0f0;
            }

            .search-bar-inner {
                display: flex;
                gap: 0.75rem;
                align-items: center;
                flex-wrap: wrap;
            }

            .search-input-wrapper {
                flex: 1;
                min-width: 200px;
                position: relative;
            }

            .search-input-wrapper .search-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                font-size: 1rem;
                pointer-events: none;
            }

            .search-input-wrapper input {
                width: 100%;
                padding: 0.7rem 1rem 0.7rem 2.75rem;
                border: 1.5px solid #e5e7eb;
                border-radius: 0.625rem;
                font-size: 0.9rem;
                color: #374151;
                background: #f9fafb;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
                outline: none;
            }

            .search-input-wrapper input:focus {
                border-color: #0070f0;
                background: #fff;
                box-shadow: 0 0 0 3px rgba(0, 112, 240, 0.1);
            }

            .search-input-wrapper input::placeholder { color: #9ca3af; }

            .btn-search {
                padding: 0.7rem 1.5rem;
                background: #0070f0;
                color: #fff;
                border: none;
                border-radius: 0.625rem;
                font-size: 0.875rem;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s, transform 0.1s;
                display: flex;
                align-items: center;
                gap: 0.4rem;
                white-space: nowrap;
            }

            .btn-search:hover { background: #005fcc; transform: translateY(-1px); }
            .btn-search:active { transform: translateY(0); }

            .btn-reset {
                padding: 0.7rem 1.2rem;
                background: #f3f4f6;
                color: #6b7280;
                border: 1.5px solid #e5e7eb;
                border-radius: 0.625rem;
                font-size: 0.875rem;
                font-weight: 500;
                cursor: pointer;
                transition: background 0.2s, color 0.2s;
                display: flex;
                align-items: center;
                gap: 0.4rem;
                text-decoration: none;
                white-space: nowrap;
            }

            .btn-reset:hover { background: #e5e7eb; color: #374151; }

            .search-active-indicator {
                display: inline-flex;
                align-items: center;
                gap: 0.4rem;
                font-size: 0.82rem;
                color: #0070f0;
                font-weight: 500;
                background: rgba(0,112,240,0.08);
                padding: 0.3rem 0.8rem;
                border-radius: 999px;
            }

            /* ===== Chats Grid ===== */
            .chats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 1rem;
            }

            /* ===== Chat Card ===== */
            .chat-card {
                background: #fff;
                border-radius: 1rem;
                border: 1.5px solid #f0f0f0;
                overflow: hidden;
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s;
                display: flex;
                flex-direction: column;
                text-decoration: none;
                color: inherit;
            }

            .chat-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                border-color: #0070f0;
                text-decoration: none;
                color: inherit;
            }

            .chat-card-header {
                padding: 1rem 1.25rem;
                background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
                border-bottom: 1px solid #e8ecf5;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .avatar-stack {
                display: flex;
                position: relative;
                width: 66px;
                height: 42px;
                flex-shrink: 0;
            }

            .avatar-stack img, .avatar-stack .avatar-placeholder {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: 2.5px solid #fff;
                object-fit: cover;
            }

            .avatar-stack .avatar-placeholder {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                font-weight: 700;
                color: #fff;
                flex-shrink: 0;
            }

            .avatar-stack img:last-child,
            .avatar-stack .avatar-placeholder:last-child {
                position: absolute;
                left: 24px;
                top: 0;
                width: 38px;
                height: 38px;
                border: 2px solid #fff;
            }

            .trip-info h6 {
                margin: 0;
                font-size: 0.9rem;
                font-weight: 700;
                color: #1e2334;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 180px;
            }

            .trip-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.72rem;
                font-weight: 600;
                padding: 0.2rem 0.6rem;
                border-radius: 999px;
                background: rgba(0, 112, 240, 0.1);
                color: #0070f0;
                margin-top: 0.2rem;
            }

            .chat-card-body {
                padding: 1rem 1.25rem;
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 0.6rem;
            }

            .person-row {
                display: flex;
                align-items: center;
                gap: 0.6rem;
            }

            .person-label {
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                width: 45px;
                flex-shrink: 0;
            }

            .person-label.user   { color: #12b76a; }
            .person-label.driver { color: #f79009; }

            .person-name {
                font-size: 0.85rem;
                font-weight: 500;
                color: #374151;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .person-phone {
                font-size: 0.78rem;
                color: #9ca3af;
                margin-left: auto;
                flex-shrink: 0;
            }

            .divider-dashed {
                border: none;
                border-top: 1px dashed #e5e7eb;
                margin: 0.25rem 0;
            }

            .last-message-row {
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
                padding: 0.5rem 0.75rem;
                background: #f9fafb;
                border-radius: 0.5rem;
            }

            .last-message-row i {
                color: #9ca3af;
                font-size: 0.85rem;
                margin-top: 0.1rem;
                flex-shrink: 0;
            }

            .last-message-text {
                font-size: 0.8rem;
                color: #6b7280;
                overflow: hidden;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                line-height: 1.4;
                flex: 1;
            }

            .chat-card-footer {
                padding: 0.75rem 1.25rem;
                border-top: 1px solid #f0f0f0;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .msg-count-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                font-size: 0.78rem;
                color: #6b7280;
            }

            .time-text {
                font-size: 0.75rem;
                color: #9ca3af;
            }

            .view-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
                font-size: 0.78rem;
                font-weight: 600;
                color: #0070f0;
                padding: 0.3rem 0.75rem;
                border-radius: 0.4rem;
                background: rgba(0,112,240,0.08);
                transition: background 0.2s;
            }

            .view-btn:hover { background: rgba(0,112,240,0.15); text-decoration: none; }

            /* ===== Empty State ===== */
            .empty-state {
                text-align: center;
                padding: 4rem 1.5rem;
                grid-column: 1 / -1;
                background: #fff;
                border-radius: 1rem;
                border: 1.5px solid #f0f0f0;
            }

            .empty-state-icon {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                font-size: 2.2rem;
                color: #6366f1;
            }

            .empty-state h4 {
                font-size: 1.15rem;
                font-weight: 700;
                color: #374151;
                margin-bottom: 0.5rem;
            }

            .empty-state p {
                color: #9ca3af;
                font-size: 0.875rem;
                margin: 0;
            }

            /* ===== Pagination ===== */
            .pagination-wrapper {
                background: #fff;
                border-radius: 1rem;
                padding: 1rem 1.5rem;
                box-shadow: 0 2px 12px rgba(0,0,0,0.06);
                border: 1px solid #f0f0f0;
            }

            @media (max-width: 768px) {
                .stats-row { grid-template-columns: 1fr 1fr; }
                .chats-grid { grid-template-columns: 1fr; }
                .search-bar-inner { flex-direction: column; align-items: stretch; }
                .btn-search, .btn-reset { width: 100%; justify-content: center; }
            }

            @media (max-width: 480px) {
                .stats-row { grid-template-columns: 1fr; }
            }
        </style>
    @endpush

    <div class="chats-page-wrapper">

        {{-- ===== Stats Row ===== --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="ki-outline ki-message-text-2 fs-1"></i></div>
                <div class="stat-info">
                    <h4>{{ $totalRooms }}</h4>
                    <span>إجمالي المحادثات</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="ki-outline ki-message-text fs-1"></i></div>
                <div class="stat-info">
                    <h4>{{ $totalMessages }}</h4>
                    <span>إجمالي الرسائل</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="ki-outline ki-calendar fs-1"></i></div>
                <div class="stat-info">
                    <h4>{{ $todayRooms }}</h4>
                    <span>محادثات اليوم</span>
                </div>
            </div>
        </div>

        {{-- ===== Search & Filters ===== --}}
        <div class="search-bar-card">
            <form method="GET" action="{{ route('admin.chats.index') }}">
                <div class="search-bar-inner">
                    {{-- Search by driver --}}
                    <div class="search-input-wrapper">
                        <span class="search-icon"><i class="ki-outline ki-truck fs-5"></i></span>
                        <input
                            type="text"
                            name="driver"
                            value="{{ request('driver') }}"
                            placeholder="اسم السائق أو رقم هاتفه..."
                            id="search-driver"
                        >
                    </div>

                    {{-- Search by user --}}
                    <div class="search-input-wrapper">
                        <span class="search-icon"><i class="ki-outline ki-user fs-5"></i></span>
                        <input
                            type="text"
                            name="user"
                            value="{{ request('user') }}"
                            placeholder="اسم العميل أو رقم هاتفه..."
                            id="search-user"
                        >
                    </div>

                    {{-- Search by trip ID --}}
                    <div class="search-input-wrapper" style="max-width: 160px;">
                        <span class="search-icon"><i class="ki-outline ki-route fs-5"></i></span>
                        <input
                            type="number"
                            name="trip"
                            value="{{ request('trip') }}"
                            placeholder="رقم الرحلة..."
                            id="search-trip"
                        >
                    </div>

                    <button type="submit" class="btn-search">
                        <i class="ki-outline ki-magnifier fs-5"></i>
                        بحث
                    </button>

                    @if(request('driver') || request('user') || request('trip'))
                        <a href="{{ route('admin.chats.index') }}" class="btn-reset">
                            <i class="ki-outline ki-cross-circle fs-5"></i>
                            مسح
                        </a>
                    @endif
                </div>
            </form>

            {{-- Active Search Indicators --}}
            @if(request('driver') || request('user') || request('trip'))
                <div class="mt-3 d-flex align-items-center gap-3 flex-wrap">
                    <span class="text-muted fs-7">نتائج البحث:</span>
                    @if(request('driver'))
                        <span class="search-active-indicator">
                            <i class="ki-outline ki-truck fs-7"></i>
                            السائق: "{{ request('driver') }}"
                        </span>
                    @endif
                    @if(request('user'))
                        <span class="search-active-indicator">
                            <i class="ki-outline ki-user fs-7"></i>
                            العميل: "{{ request('user') }}"
                        </span>
                    @endif
                    @if(request('trip'))
                        <span class="search-active-indicator">
                            <i class="ki-outline ki-route fs-7"></i>
                            رحلة رقم: #{{ request('trip') }}
                        </span>
                    @endif
                    <span class="text-muted fs-7">&mdash; {{ $rooms->total() }} نتيجة</span>
                </div>
            @endif
        </div>

        {{-- ===== Chats Grid ===== --}}
        <div class="chats-grid">
            @forelse ($rooms as $room)
                @php
                    $user      = $room->trip?->user;
                    $driver    = $room->trip?->driver;
                    $latestMsg = $room->latest_message?->first();
                    $msgCount  = $room->chat?->count() ?? 0;
                @endphp
                <a class="chat-card" href="{{ route('admin.chats.single', $room->id) }}">

                    {{-- Card Header --}}
                    <div class="chat-card-header">
                        <div class="avatar-stack">
                            @if($user?->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->full_name }}">
                            @else
                                <div class="avatar-placeholder">{{ mb_substr($user?->full_name ?? 'U', 0, 1) }}</div>
                            @endif
                            @if($driver?->avatar_url)
                                <img src="{{ $driver->avatar_url }}" alt="{{ $driver->full_name }}">
                            @else
                                <div class="avatar-placeholder" style="background: linear-gradient(135deg, #f79009 0%, #dc6803 100%);">
                                    {{ mb_substr($driver?->full_name ?? 'D', 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div class="trip-info flex-grow-1 overflow-hidden">
                            <h6>{{ $user?->full_name ?? 'عميل غير معروف' }}</h6>
                            <span class="trip-badge">
                                <i class="ki-outline ki-route fs-8"></i>
                                رحلة #{{ $room->trip?->id ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    {{-- Card Body --}}
                    <div class="chat-card-body">
                        <div class="person-row">
                            <span class="person-label user">عميل</span>
                            <span class="person-name">{{ $user?->full_name ?? 'غير معروف' }}</span>
                            @if($user?->phone)
                                <span class="person-phone">{{ $user->phone }}</span>
                            @endif
                        </div>

                        <hr class="divider-dashed">

                        <div class="person-row">
                            <span class="person-label driver">سائق</span>
                            <span class="person-name">{{ $driver?->full_name ?? 'غير معروف' }}</span>
                            @if($driver?->phone)
                                <span class="person-phone">{{ $driver->phone }}</span>
                            @endif
                        </div>

                        @if($latestMsg)
                            <div class="last-message-row">
                                <i class="ki-outline ki-message-text fs-6"></i>
                                <span class="last-message-text">{{ $latestMsg->message }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Card Footer --}}
                    <div class="chat-card-footer">
                        <div class="msg-count-badge">
                            <i class="ki-outline ki-message-text-2"></i>
                            {{ $msgCount }} رسالة
                        </div>
                        <span class="time-text">
                            @if($latestMsg)
                                {{ $latestMsg->created_at->diffForHumans() }}
                            @else
                                {{ $room->created_at->diffForHumans() }}
                            @endif
                        </span>
                        <span class="view-btn">
                            عرض
                            <i class="ki-outline ki-arrow-left fs-7"></i>
                        </span>
                    </div>

                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ki-outline ki-message-text-2"></i>
                    </div>
                    <h4>
                        @if(request('driver') || request('user') || request('trip'))
                            لا توجد نتائج مطابقة
                        @else
                            لا توجد محادثات
                        @endif
                    </h4>
                    <p>
                        @if(request('driver') || request('user') || request('trip'))
                            جرّب تغيير كلمات البحث أو <a href="{{ route('admin.chats.index') }}">عرض الكل</a>
                        @else
                            عندما يبدأ العملاء محادثات ستظهر هنا
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        {{-- ===== Pagination ===== --}}
        @if($rooms->hasPages())
            <div class="pagination-wrapper d-flex justify-content-between align-items-center flex-wrap gap-3">
                <span class="text-muted fs-7">
                    عرض {{ $rooms->firstItem() }}–{{ $rooms->lastItem() }} من {{ $rooms->total() }} محادثة
                </span>
                {{ $rooms->appends(request()->query())->links() }}
            </div>
        @endif

    </div>
@endsection