@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">الطلبات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-muted">التعيين اليدوي</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">السائقين المتاحين</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-6">
            <div class="card-header">
                <h3 class="card-title">بيانات الطلب #{{ $order->id }}</h3>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="fw-bold text-muted d-block">العميل:</label>
                    <span class="text-dark fw-bold fs-6">{{ $order->user->full_name ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <label class="fw-bold text-muted d-block">موقع الانطلاق:</label>
                    <span class="text-muted fs-7">{{ $order->source_address }}</span>
                </div>
                <div class="mb-4">
                    <label class="fw-bold text-muted d-block">الوجهة:</label>
                    <span class="text-muted fs-7">{{ $order->destination_address }}</span>
                </div>
                <div class="mb-4">
                    <label class="fw-bold text-muted d-block">الخدمة:</label>
                    <span class="badge badge-light-info fw-bold">{{ $order->service->title ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <label class="fw-bold text-muted d-block">السعر المتوقع:</label>
                    <span class="text-primary fw-bold fs-5">{{ $order->offer_rate }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title">السائقين المتاحين حالياً والمتوافقين مع الخدمة</h3>
            </div>
            <div class="card-body">
                @if($drivers->isEmpty())
                    <div class="alert alert-warning">
                        <i class="ki-outline ki-information fs-2"></i>
                        لا يوجد سائقين متاحين حالياً (متصلين وغير منشغلين برحلة أخرى) ومتوافقين مع نوع الخدمة المطلوبة.
                    </div>
                @endif
                
                <div class="row">
                    @foreach($drivers as $driver)
                        <div class="col-md-6 mb-5">
                            <div class="card border border-dashed border-gray-300 p-6 h-100">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px symbol-circle me-5">
                                        <div class="symbol-label fs-2 fw-bold text-success">{{ substr($driver->full_name, 0, 1) }}</div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $driver->full_name }}</span>
                                        <span class="text-muted d-block fs-7">{{ $driver->phone_number }}</span>
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="ki-outline ki-car fs-6 me-2 text-primary"></span>
                                        <span class="text-muted fs-7">السيارة: <strong>{{ $driver->profile->driver_cars->brand->title ?? '' }} {{ $driver->profile->driver_cars->model->title ?? '' }}</strong></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="ki-outline ki-mask fs-6 me-2 text-primary"></span>
                                        <span class="text-muted fs-7">الخدمة: <strong>{{ $driver->profile->service->title ?? 'N/A' }}</strong></span>
                                    </div>
                                </div>
                                <form action="{{ route('admin.orders.manual_assign', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                                    <button type="submit" class="btn btn-primary w-100" onclick="return confirm('هل أنت متأكد من تعيين هذا السائق وتخطي نظام الدفع التقليدي؟ سيتم خصم العمولة من محفظة السائق فوراً.')">
                                        تعيين وتخطي الدفع
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
