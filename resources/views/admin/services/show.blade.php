@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الخدمات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3">{{ trans('global.view') }} {{ trans('cruds.service.title_singular') }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                    <tbody>
                        <tr>
                            <th class="fw-semibold w-25 text-gray-700">{{ trans('cruds.service.fields.id') }}</th>
                            <td>{{ $row->id }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.title') }}</th>
                            <td>{{ $row->title }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.admin_commission') }}</th>
                            <td>{{ $row->admin_commission ? $row->admin_commission . '%' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.enable') }}</th>
                            <td>
                                <span class="badge {{ $row->enable ? 'badge-light-success' : 'badge-light-danger' }}">
                                    {{ $row->enable ? trans('global.active') : trans('global.inactive') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.km_charge') }}</th>
                            <td>{{ $row->km_charge ? number_format($row->km_charge, 2) . ' EGP' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">شرائح التسعير بالكيلومتر</th>
                            <td>
                                @if(!empty($row->price_tiers) && is_array($row->price_tiers) && count($row->price_tiers) > 0)
                                    <div class="mb-2">
                                        <span class="badge bg-light-primary text-primary fw-bold">
                                            طريقة الحساب: {{ $row->tier_pricing_type === 'cumulative' ? 'تراكمي تدرجي' : 'سعر الشريحة المطبقة' }}
                                        </span>
                                    </div>
                                    <table class="table table-sm table-bordered max-w-400px border rounded">
                                        <thead class="table-light">
                                            <tr>
                                                <th>من (كم)</th>
                                                <th>إلى (كم)</th>
                                                <th>سعر الكيلو (ج.م)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($row->price_tiers as $tier)
                                                <tr>
                                                    <td>{{ $tier['from_km'] ?? 0 }} كم</td>
                                                    <td>{{ isset($tier['to_km']) && $tier['to_km'] !== '' ? $tier['to_km'] . ' كم' : 'مفتوح (أعلى من)' }}</td>
                                                    <td class="fw-bold text-success">{{ number_format($tier['price_per_km'] ?? 0, 2) }} ج.م</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <span class="text-muted">غير مخصص (يعتمد على سعر الكيلومتر الافتراضي)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.offer_rate') }}</th>
                            <td>
                                <span class="badge {{ $row->offer_rate ? 'badge-light-warning' : 'badge-light-secondary' }}">
                                    {{ $row->offer_rate ? trans('global.yes') : trans('global.no') }}
                                </span>
                            </td>
                        </tr>
                        @if($row->service_type === 'shipping')
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.weight') }}</th>
                            <td>{{ $row->weight }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.length') }}</th>
                            <td>{{ $row->length }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.width') }}</th>
                            <td>{{ $row->width }}</td>
                        </tr>
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.height') }}</th>
                            <td>{{ $row->height }}</td>
                        </tr>
                        @endif
                        @if($row->thumbnail && count($row->thumbnail) > 0)
                        <tr>
                            <th class="fw-semibold text-gray-700">{{ trans('cruds.service.fields.image') }}</th>
                            <td>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($row->thumbnail as $image)
                                        <img src="{{ CheckPhoto($image['url']) }}" alt="{{ $row->title }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <!--end::Table-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.services.edit', $row) }}" class="btn btn-primary me-2">
                        {{ trans('global.edit') }}
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                        {{ trans('global.back') }}
                    </a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection