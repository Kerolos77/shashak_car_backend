@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الطلبات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<div class="row">
    <div class="card bg-blueGray-100">
        <div class="card-header">
            <div class="card-header-container">
                <h6 class="card-title">
                    {{ trans('global.view') }}
                    {{ trans('cruds.order.title_singular') }}:
                    {{ trans('cruds.order.fields.id') }}
                    {{ $order->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.id') }}
                            </th>
                            <td>
                                {{ $order->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.accepted_driver') }}
                            </th>
                            <td>
                                {{ $order->accepted_driver }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.admin_commission') }}
                            </th>
                            <td>
                                {{ $order->admin_commission }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.destination_location_name') }}
                            </th>
                            <td>
                                {{ $order->destination_location_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.destination_location_l_at_lng') }}
                            </th>
                            <td>
                                {{ $order->destination_location_l_at_lng }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.distance') }}
                            </th>
                            <td>
                                {{ $order->distance }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.distance_type') }}
                            </th>
                            <td>
                                {{ $order->distance_type }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.driver') }}
                            </th>
                            <td>
                                {{ $order->driver }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.final_rate') }}
                            </th>
                            <td>
                                {{ $order->final_rate }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.offer_rate') }}
                            </th>
                            <td>
                                {{ $order->offer_rate }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.otp') }}
                            </th>
                            <td>
                                {{ $order->otp }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.payment_status') }}
                            </th>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $order->payment_status ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.payment_type') }}
                            </th>
                            <td>
                                {{ $order->payment_type }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.position') }}
                            </th>
                            <td>
                                {{ $order->position }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.rejected_driver') }}
                            </th>
                            <td>
                                {{ $order->rejected_driver }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.service') }}
                            </th>
                            <td>
                                {{ $order->service }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.source_location_l_at_lng') }}
                            </th>
                            <td>
                                {{ $order->source_location_l_at_lng }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.source_location_name') }}
                            </th>
                            <td>
                                {{ $order->source_location_name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.status') }}
                            </th>
                            <td>
                                {{ $order->status }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.tax_list') }}
                            </th>
                            <td>
                                {{ $order->tax_list }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.update_date') }}
                            </th>
                            <td>
                                {{ $order->update_date }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.order.fields.user') }}
                            </th>
                            <td>
                                @if($order->user)
                                    <span class="badge badge-relationship">{{ $order->user->name ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('order_edit')
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection