@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">الطلبات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">التعيين اليدوي</li>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h3 class="card-title">طلبات بانتظار التعيين أو الدفع (Manual Assignment)</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-3">
                <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-100px rounded-start">رقم الطلب</th>
                        <th class="min-w-125px">العميل</th>
                        <th class="min-w-125px">الخدمة</th>
                        <th class="min-w-125px">الحالة الحالية</th>
                        <th class="min-w-125px">السعر المتفق عليه</th>
                        <th class="min-w-100px text-end rounded-end">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="text-dark fw-bold text-hover-primary mb-1 fs-6">#{{ $order->id }}</span>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold d-block fs-7">{{ $order->user->full_name ?? 'N/A' }}</span>
                                <span class="text-muted fs-8">{{ $order->user->phone_number ?? '' }}</span>
                            </td>
                            <td>
                                <span class="text-muted fw-semibold d-block fs-7">{{ $order->service->title ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-light-warning fw-bold">{{ $order->status }}</span>
                            </td>
                            <td>
                                <span class="text-dark fw-bold d-block fs-7">{{ $order->offer_rate }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.manual_drivers', $order->id) }}" class="btn btn-sm btn-light-primary">
                                    تعيين سائق
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">لا توجد طلبات معلقة حالياً تستوجب التدخل اليدوي.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
