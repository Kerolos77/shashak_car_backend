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
            <div class="card-header border-0 pt-6 d-flex align-items-center justify-content-between">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3">{{ trans('global.view') }} {{ trans('cruds.user.title_singular') }}</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('admin.users.export-pdf', $user->id) }}" class="btn btn-sm btn-danger px-4" target="_blank">
                        <i class="ki-outline ki-document fs-5 me-1"></i>
                        تصدير ملف أمني رسمي (PDF)
                    </a>
                </div>
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
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_user_view_identity_tab">
                            توثيق الهوية بالذكاء الاصطناعي
                            @if($user->identity)
                                @if($user->identity->status === 'verified')
                                    <span class="badge badge-light-success ms-2">موثق</span>
                                @else
                                    <span class="badge badge-light-danger ms-2">فشل التحقق</span>
                                @endif
                            @else
                                <span class="badge badge-light-dark ms-2">غير موثق</span>
                            @endif
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
                                                    <a href="{{ $user->imageurl }}" target="_blank" style="display: inline-block"><img src="{{ $user->imageurl }}" style="max-height:80px; border-radius:8px;"></a>
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
                                                                            <img src="{{ $referral->imageurl }}" alt="{{ $referral->name }}" class="w-100" />
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

                    <div class="tab-pane fade" id="kt_user_view_identity_tab">
                        <div class="card card-flush mb-6 mb-xl-9">
                            <div class="card-header mt-6">
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">بيانات الهوية والتحقق بالذكاء الاصطناعي</h2>
                                </div>
                            </div>
                            <div class="card-body p-9 pt-4">
                                @if($user->identity)
                                    <div class="mb-8">
                                        <div class="row g-5">
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-gray-300 rounded px-6 py-4 text-center">
                                                    <div class="fs-6 text-gray-400 fw-bold mb-2">وجه البطاقة</div>
                                                    <a href="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->front_image) }}" target="_blank">
                                                        <img src="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->front_image) }}" class="img-fluid rounded max-h-150px" style="max-height: 150px;" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-gray-300 rounded px-6 py-4 text-center">
                                                    <div class="fs-6 text-gray-400 fw-bold mb-2">ظهر البطاقة</div>
                                                    <a href="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->back_image) }}" target="_blank">
                                                        <img src="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->back_image) }}" class="img-fluid rounded max-h-150px" style="max-height: 150px;" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border border-dashed border-gray-300 rounded px-6 py-4 text-center">
                                                    <div class="fs-6 text-gray-400 fw-bold mb-2">صورة السيلفي الحية</div>
                                                    <a href="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->selfie_image) }}" target="_blank">
                                                        <img src="{{ url('files/UserIdentity/' . $user->id . '/' . $user->identity->selfie_image) }}" class="img-fluid rounded max-h-150px" style="max-height: 150px;" />
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>حالة التوثيق</th>
                                                <td>
                                                    @if($user->identity->status === 'verified')
                                                        <span class="badge badge-success fs-6">Passed (موثق مقبول)</span>
                                                    @else
                                                        <span class="badge badge-danger fs-6">Failed (مرفوض)</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>الرقم القومي المستخرج</th>
                                                <td><span class="badge badge-light-dark fw-bolder fs-6">{{ $user->identity->id_number }}</span></td>
                                            </tr>
                                            <tr>
                                                <th>نسبة تطابق الوجه بالذكاء الاصطناعي</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2 fw-bolder">{{ $user->identity->ai_face_similarity }}%</span>
                                                        <div class="progress h-6px w-100px bg-light-success">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $user->identity->ai_face_similarity }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @if($user->identity->ai_rejection_reason)
                                                <tr class="table-danger">
                                                    <th>سبب الرفض المعروض للمستخدم</th>
                                                    <td class="text-danger fw-bold fs-6">{{ $user->identity->ai_rejection_reason }}</td>
                                                </tr>
                                            @endif
                                            @if(is_array($user->identity->ai_verification_report))
                                                <tr>
                                                    <th>تقرير المطابقة بين الوجه والظهر</th>
                                                    <td>
                                                        @if($user->identity->ai_verification_report['front_back_matched'] ?? false)
                                                            <span class="text-success"><i class="fa fa-check-circle me-1"></i> متطابقان وينتميان لنفس البطاقة</span>
                                                        @else
                                                            <span class="text-danger"><i class="fa fa-times-circle me-1"></i> غير متطابقان أو هناك اختلاف في قالب البطاقة</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>فحص الصور المعدلة ومصورة الشاشات</th>
                                                    <td>
                                                        <ul class="list-unstyled mb-0">
                                                            <li>سيلفي حقيقي مباشر: {!! ($user->identity->ai_verification_report['is_real_selfie'] ?? false) ? '<span class="text-success">نعم</span>' : '<span class="text-danger">لا (اشتباه تزييف أو تصوير شاشة)</span>' !!}</li>
                                                            <li>وجه البطاقة طبيعي: {!! ($user->identity->ai_verification_report['is_real_id_front'] ?? false) ? '<span class="text-success">نعم</span>' : '<span class="text-danger">لا (اشتباه تزييف أو تصوير شاشة)</span>' !!}</li>
                                                            <li>ظهر البطاقة طبيعي: {!! ($user->identity->ai_verification_report['is_real_id_back'] ?? false) ? '<span class="text-success">نعم</span>' : '<span class="text-danger">لا (اشتباه تزييف أو تصوير شاشة)</span>' !!}</li>
                                                            <li>تعديل رقمي/توليد ذكاء اصطناعي: {!! ($user->identity->ai_verification_report['ai_generated_or_modified_detected'] ?? false) ? '<span class="text-danger">تم اكتشافه!</span>' : '<span class="text-success">لا يوجد تعديل</span>' !!}</li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>الاسم المستخرج بالكامل</th>
                                                    <td>{{ $user->identity->ai_verification_report['extracted_full_name'] ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>ملاحظات الذكاء الاصطناعي التفصيلية</th>
                                                    <td><p class="text-gray-700 fs-7" style="white-space: pre-wrap;">{{ $user->identity->ai_verification_report['detailed_report'] ?? '-' }}</p></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-10">
                                        <i class="fa fa-id-card fs-3x text-muted mb-4"></i>
                                        <p class="text-gray-500 fs-5">لم يقم هذا العميل برفع أي مستندات أو طلب توثيق الهوية حتى الآن.</p>
                                    </div>
                                @endif
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
