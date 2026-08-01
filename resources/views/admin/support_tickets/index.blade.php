@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-warning p-4 rounded-circle">
                        <i class="ki-outline ki-message-question fs-2x text-warning"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">مركز تذاكر الشكاوى والدعم الفني (Support Tickets Center)</h2>
                        <div class="text-muted fs-6">متابعة وحل شكاوى العملاء والسائقين والنزاعات المتعلقة بالرحلات.</div>
                    </div>
                </div>
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
                                <th>رقم التذكرة</th>
                                <th>صاحب الشكوى (العميل)</th>
                                <th>رقم الرحلة</th>
                                <th>موضوع الشكوى</th>
                                <th>الأولوية</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td>
                                        <span class="badge bg-light-primary text-primary fw-bolder px-3 py-2">
                                            {{ $row->ticket_number }}
                                        </span>
                                    </td>
                                    <td class="fw-bold">
                                        {{ $row->user->name ?? 'عميل ' . $row->user_id }}
                                        <div class="text-muted fs-7">{{ $row->user->phone ?? '' }}</div>
                                    </td>
                                    <td>
                                        @if($row->order_id)
                                            <span class="badge bg-light-info text-info">#{{ $row->order_id }}</span>
                                        @else
                                            <span class="text-muted">عام (بدون طلب)</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-gray-800">{{ Str::limit($row->subject, 35) }}</td>
                                    <td>
                                        @if($row->priority === 'urgent')
                                            <span class="badge bg-danger text-white">عاجل جداً</span>
                                        @elseif($row->priority === 'high')
                                            <span class="badge bg-light-danger text-danger">عالي</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">عادي</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->status === 'open')
                                            <span class="badge bg-light-warning text-warning fw-bold">مفتوحة (جديدة)</span>
                                        @elseif($row->status === 'in_review')
                                            <span class="badge bg-light-info text-info fw-bold">قيد المراجعة</span>
                                        @elseif($row->status === 'resolved')
                                            <span class="badge bg-light-success text-success fw-bold">تم الحل والتعويض</span>
                                        @else
                                            <span class="badge bg-light-secondary text-muted fw-bold">مغلقة</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-7">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $row->id) }}" class="btn btn-sm btn-light-primary fw-bold">
                                            <i class="ki-outline ki-eye fs-4 me-1"></i> عرض والرد
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-6 text-muted">لا توجد تذاكر شكاوى حالياً</td>
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
