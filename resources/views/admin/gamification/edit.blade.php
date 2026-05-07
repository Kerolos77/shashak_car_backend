@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">{{ trans('cruds.gamification.title') }}</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">{{ trans('cruds.gamification.title_singular') }}</li>
@endsection

@section('content')
@section('title', $pageTitle)
@section('pageName', $pageTitle)

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <form action="{{ route('admin.gamification.update') }}" method="POST">
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-4">
                            <h4 class="text-primary"><i class="ti ti-trophy me-2"></i>{{ trans('cruds.gamification.title') }}</h4>
                            <p class="text-muted small">إعدادات نظام النقاط والمكافآت والخصومات التلقائية للرحلات</p>
                        </div>

                        <!-- User Section -->
                        <div class="col-md-6">
                            <div class="card border shadow-none mb-4">
                                <div class="card-header bg-light-primary py-3">
                                    <h5 class="mb-0"><i class="ti ti-user me-2"></i>{{ trans('cruds.user.title') }}</h5>
                                </div>
                                <div class="card-body pt-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('cruds.gamification.fields.points_per_trip_user') }}</label>
                                        <input type="number" value="{{ $row->points_per_trip_user ?? 0 }}" name="points_per_trip_user" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('cruds.gamification.fields.points_visa_bonus_user') }}</label>
                                        <input type="number" value="{{ $row->points_visa_bonus_user ?? 0 }}" name="points_visa_bonus_user" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-danger font-weight-bold">{{ trans('cruds.gamification.fields.points_cancel_penalty_user') }}</label>
                                        <input type="number" value="{{ $row->points_cancel_penalty_user ?? 0 }}" name="points_cancel_penalty_user" class="form-control border-danger">
                                        <div class="form-text text-danger">سيتم خصم هذه النقاط من العميل عند إلغاء الرحلة</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Section -->
                        <div class="col-md-6">
                            <div class="card border shadow-none mb-4">
                                <div class="card-header bg-light-success py-3">
                                    <h5 class="mb-0"><i class="ti ti-car me-2"></i>{{ trans('cruds.driver.title') }}</h5>
                                </div>
                                <div class="card-body pt-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('cruds.gamification.fields.points_per_trip_driver') }}</label>
                                        <input type="number" value="{{ $row->points_per_trip_driver ?? 0 }}" name="points_per_trip_driver" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('cruds.gamification.fields.points_visa_bonus_driver') }}</label>
                                        <input type="number" value="{{ $row->points_visa_bonus_driver ?? 0 }}" name="points_visa_bonus_driver" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ trans('cruds.gamification.fields.points_five_star_bonus_driver') }}</label>
                                        <input type="number" value="{{ $row->points_five_star_bonus_driver ?? 0 }}" name="points_five_star_bonus_driver" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-danger font-weight-bold">{{ trans('cruds.gamification.fields.points_cancel_penalty_driver') }}</label>
                                        <input type="number" value="{{ $row->points_cancel_penalty_driver ?? 0 }}" name="points_cancel_penalty_driver" class="form-control border-danger">
                                        <div class="form-text text-danger">سيتم خصم هذه النقاط من السائق عند إلغاء الرحلة</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-5 waves-effect waves-light">
                                <i class="ti ti-device-floppy me-1"></i> {{ trans('global.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
