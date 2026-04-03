@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> الأسئلة الشائعة </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض التفاصيل</li>
    @endsection

@section('content')
<!--begin::Container-->
<div class="container-fluid">
    <!--begin::Card-->
    <div class="card card-custom gutter-b">
        <!--begin::Header-->
        <div class="card-header border-0 py-5">
            <div class="card-title">
                <h3 class="card-label">
                    <span class="text-primary">{{ trans('global.view') }}</span>
                    <span class="text-muted">{{ trans('cruds.faq.title_singular') }}</span>
                    <span class="text-dark">#{{ $faq->id }}</span>
                </h3>
            </div>
            <div class="card-toolbar">
                @can('faq_edit')
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-primary font-weight-bold mr-2">
                        <span class="svg-icon svg-icon-md">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Edit.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"/>
                                <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        {{ trans('global.edit') }}
                    </a>
                @endcan
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-light-primary font-weight-bold">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-left.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1"/>
                                <path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997)"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                    {{ trans('global.back') }}
                </a>
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Body-->
        <div class="card-body pt-0 pb-5">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table table-head-custom table-head-bg table-borderless table-vertical-center">
                    <tbody>
                        <tr>
                            <th class="pl-0 text-muted" style="width: 250px">{{ trans('cruds.faq.fields.id') }}</th>
                            <td class="pr-0">
                                <span class="text-dark-75 font-weight-bold">{{ $faq->id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0 text-muted">{{ trans('cruds.faq.fields.description') }}</th>
                            <td class="pr-0">
                                <span class="text-dark-75 font-weight-bold">{{ $faq->description }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0 text-muted">{{ trans('cruds.faq.fields.enable') }}</th>
                            <td class="pr-0">
                                <span class="switch switch-sm">
                                    <label>
                                        <input type="checkbox" disabled {{ $faq->enable ? 'checked' : '' }} />
                                        <span></span>
                                    </label>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="pl-0 text-muted">{{ trans('cruds.faq.fields.title') }}</th>
                            <td class="pr-0">
                                <span class="text-dark-75 font-weight-bold">{{ $faq->title }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!--end::Table-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Card-->
</div>
<!--end::Container-->
@endsection