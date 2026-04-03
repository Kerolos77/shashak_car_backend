@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted"> {{ trans('cruds.user.title') }} </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('global.view') }}</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3">{{ trans('global.view') }} {{ trans('cruds.user.title_singular') }}</span>
                </h3>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Tabs-->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">{{ trans('global.overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_overview_referrals_tab">
                            {{ trans('cruds.referral.tab_title') }}
                            <span class="badge badge-light-primary ms-2">{{ $user->referrals->count() }}</span>
                        </a>
                    </li>
                </ul>
                <!--end::Tabs-->

                <!--begin::Tab Content-->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_user_view_overview_tab">
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">{{ trans('cruds.user.fields.user_information') }}</h2>
                                </div>
                            </div>
                            <div class="card-body p-9 pt-4">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.id') }}</th>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.name') }}</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.email') }}</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.referral.fields.referral_code') }}</th>
                                            <td><span class="badge badge-light-success fw-bolder fs-6">{{ $user->referral_code }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.referral.fields.referral_by') }}</th>
                                            <td>
                                                @if($user->referrer)
                                                    <a href="{{ route('admin.users.show', $user->referrer->id) }}">{{ $user->referrer->name }}</a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.roles') }}</th>
                                            <td>
                                                @foreach($user->roles as $key => $role)
                                                    <span class="badge badge-info">{{ $role->title }}</span>
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.phone_number') }}</th>
                                            <td>{{ $user->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.profile_pic') }}</th>
                                            <td>
                                                @if($user->profile_pic)
                                                    <a href="{{ $user->profile_pic->getUrl() }}" target="_blank" style="display: inline-block">
                                                        <img src="{{ $user->profile_pic->getUrl('thumb') }}">
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.user.fields.wallet_amount') }}</th>
                                            <td>{{ $user->wallet_amount }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="kt_user_view_overview_referrals_tab">
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-body p-9">
                                <!-- Referral Summary -->
                                <div class="row mb-8">
                                    <div class="col-md-6">
                                        <div class="border border-dashed border-gray-300 rounded px-6 py-4">
                                            <div class="fs-6 text-gray-400 fw-bold">{{ trans('cruds.referral.total_referrals') }}</div>
                                            <div class="fs-2 fw-bolder text-gray-800">{{ $user->referrals->count() }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border border-dashed border-gray-300 rounded px-6 py-4">
                                            <div class="fs-6 text-gray-400 fw-bold">{{ trans('cruds.referral.total_earnings') }}</div>
                                            <div class="fs-2 fw-bold text-success">{{ number_format($user->total_referral_earnings, 2) }} {{ trans('cruds.referral.currency') }}</div>
                                        </div>
                                    </div>
                                </div>
                    
                                <!-- Referred Users Table -->
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-200px">{{ trans('cruds.referral.invited_user') }}</th>
                                                <th class="min-w-150px">{{ trans('cruds.user.fields.phone_number') }}</th>
                                                <th class="min-w-150px">{{ trans('cruds.referral.join_date') }}</th>
                                                <th class="min-w-100px text-end">{{ trans('cruds.referral.bonus_earned') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bold text-gray-600">
                                            @forelse($user->referrals as $referral)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                                <a href="{{ route('admin.users.show', $referral->id) }}">
                                                                    @if($referral->profile_pic)
                                                                        <div class="symbol-label">
                                                                            <img src="{{ $referral->profile_pic->getUrl('thumb') }}" alt="{{ $referral->name }}" class="w-100" />
                                                                        </div>
                                                                    @else
                                                                        <div class="symbol-label fs-3 bg-light-danger text-danger">
                                                                            {{ substr($referral->name, 0, 1) }}
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="d-flex flex-column">
                                                                <a href="{{ route('admin.users.show', $referral->id) }}" class="text-gray-800 text-hover-primary mb-1">{{ $referral->name }}</a>
                                                                <span>{{ $referral->email }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $referral->phone_number }}</td>
                                                    <td>{{ $referral->created_at->format('M d, Y') }}</td>
                                                    <td class="text-end text-success fs-5">
                                                        +{{ $user->getReferralBonusVal($referral) }} {{ trans('cruds.referral.currency') }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted fs-6 py-5">
                                                        {{ trans('global.no_results') }}
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Tab Content-->

                <!--begin::Actions-->
                <div class="d-flex justify-content-end mt-4">
                    @can('user_edit')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                            {{ trans('global.edit') }}
                        </a>
                    @endcan
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
