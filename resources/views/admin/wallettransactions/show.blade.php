@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> معاملات المحفظة </li>
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
                    {{ trans('cruds.walletTransaction.title_singular') }}:
                    {{ trans('cruds.walletTransaction.fields.id') }}
                    {{ $walletTransaction->id }}
                </h6>
            </div>
        </div>

        <div class="card-body">
            <div class="pt-3">
                <table class="table table-view">
                    <tbody class="bg-white">
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.id') }}
                            </th>
                            <td>
                                {{ $walletTransaction->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.amount') }}
                            </th>
                            <td>
                                {{ $walletTransaction->amount }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.note') }}
                            </th>
                            <td>
                                {{ $walletTransaction->note }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.order_type') }}
                            </th>
                            <td>
                                {{ $walletTransaction->order_type }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.payment_type') }}
                            </th>
                            <td>
                                {{ $walletTransaction->payment_type }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.transaction') }}
                            </th>
                            <td>
                                {{ $walletTransaction->transaction }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.user') }}
                            </th>
                            <td>
                                @if($walletTransaction->user)
                                    <span class="badge badge-relationship">{{ $walletTransaction->user->name ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.walletTransaction.fields.user_type') }}
                            </th>
                            <td>
                                {{ $walletTransaction->user_type }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                @can('wallet_transaction_edit')
                    <a href="{{ route('admin.wallet-transactions.edit', $walletTransaction) }}" class="btn btn-indigo mr-2">
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-secondary">
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection