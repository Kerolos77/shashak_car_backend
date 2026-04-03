<div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    {{ trans('global.notification_page.notifications') }}
                </h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('admin.home') }}" class="text-muted text-hover-primary">{{ trans('global.dashboard') }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{ trans('global.notification_page.notifications') }}</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card card-flush shadow-sm">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bold m-0"><i class="ki-outline ki-notification-on fs-2 text-primary me-2"></i> {{ trans('global.notification_page.send_custom_notification') }}</h3>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <p class="text-gray-500 mb-8">{{ trans('global.notification_page.notification_desc') }}</p>

                    @if ($successMessage)
                        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                            <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-success">{{ trans('global.notification_page.success') }}</h4>
                                <span>{{ $successMessage }}</span>
                            </div>
                            <button type="button" wire:click="$set('successMessage', '')" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto">
                                <i class="ki-outline ki-cross fs-1 text-success"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errorMessage)
                        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                            <i class="ki-outline ki-information-5 fs-2hx text-danger me-4"></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 text-danger">{{ trans('global.notification_page.error') }}</h4>
                                <span>{{ $errorMessage }}</span>
                            </div>
                            <button type="button" wire:click="$set('errorMessage', '')" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto">
                                <i class="ki-outline ki-cross fs-1 text-danger"></i>
                            </button>
                        </div>
                    @endif

                    <form wire:submit.prevent="send" class="form">
                        
                        <!-- Target Selection -->
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3"><span class="required">{{ trans('global.notification_page.target_audience') }}</span></div>
                            </div>
                            <div class="col-xl-9">
                                <select wire:model="target" class="form-control form-control-solid @error('target') is-invalid @enderror">
                                    <option value="all_users">{{ trans('global.notification_page.all_users') }}</option>
                                    <option value="all_drivers">{{ trans('global.notification_page.all_drivers') }}</option>
                                    <option value="specific_user">{{ trans('global.notification_page.specific_user') }}</option>
                                </select>
                                @error('target')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Specific User Identifier -->
                        @if ($target === 'specific_user')
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3"><span class="required">{{ trans('global.notification_page.user_identifier') }}</span></div>
                            </div>
                            <div class="col-xl-9 position-relative">
                                <input wire:model.debounce.300ms="search" type="text" class="form-control form-control-solid @error('user_id') is-invalid @enderror" placeholder="{{ trans('global.notification_page.search_user') }}" autocomplete="off" />
                                
                                <!-- Search Dropdown Results -->
                                @if(strlen($search) > 1 && $user_id == '')
                                    <div class="position-absolute w-100 bg-white shadow border rounded mt-1 overflow-auto" style="max-height: 250px; z-index: 9999; top: 100%;">
                                        @php
                                            $users = \App\Models\User::where('id', 'like', "%{$search}%")
                                                ->orWhere('name', 'like', "%{$search}%")
                                                ->orWhere('email', 'like', "%{$search}%")
                                                ->orWhere('phone_number', 'like', "%{$search}%")
                                                ->take(20)->get();
                                        @endphp

                                        @if($users->count() > 0)
                                            <div class="list-group list-group-flush">
                                                @foreach($users as $u)
                                                    <a href="javascript:void(0)" wire:click="selectUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->phone_number }}')" class="list-group-item list-group-item-action p-3 border-bottom text-hover-primary">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="fw-bold">{{ $u->name }}</div>
                                                            <span class="badge badge-light-primary fw-bolder">ID: {{ $u->id }}</span>
                                                        </div>
                                                        <div class="text-muted fs-7 mt-1"><i class="ki-outline ki-phone me-1"></i>{{ $u->phone_number }}  |  <i class="ki-outline ki-sms me-1"></i>{{ $u->email }}</div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-4 text-center text-muted">{{ trans('global.notification_page.no_search_results') }}</div>
                                        @endif
                                    </div>
                                @endif
                                
                                <!-- Hidden real user_id field for validation -->
                                <input type="hidden" wire:model="user_id">
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif



                        <!-- Title -->
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3"><span class="required">{{ trans('global.notification_page.notification_title') }}</span></div>
                            </div>
                            <div class="col-xl-9">
                                <input wire:model.defer="title" type="text" class="form-control form-control-solid @error('title') is-invalid @enderror" placeholder="{{ trans('global.notification_page.notification_title_placeholder') }}" />
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3"><span class="required">{{ trans('global.notification_page.notification_body') }}</span></div>
                            </div>
                            <div class="col-xl-9">
                                <textarea wire:model.defer="body" rows="4" class="form-control form-control-solid @error('body') is-invalid @enderror" placeholder="{{ trans('global.notification_page.notification_body_placeholder') }}"></textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="row mb-8">
                            <div class="col-xl-3">
                                <div class="fs-6 fw-semibold mt-2 mb-3">{{ trans('global.notification_page.attached_image') }}</div>
                            </div>
                            <div class="col-xl-9">
                                <input type="file" wire:model="image" class="form-control form-control-solid @error('image') is-invalid @enderror" accept="image/*" />
                                <div class="text-muted fs-7 mt-2">{{ trans('global.notification_page.attached_image_desc') }}</div>
                                
                                <div wire:loading wire:target="image" class="text-primary mt-2">
                                    <span class="spinner-border spinner-border-sm align-middle me-2"></span> {{ trans('global.notification_page.uploading_image') }}
                                </div>

                                @if ($image)
                                    <div class="mt-4">
                                        <img src="{{ $image->temporaryUrl() }}" class="rounded shadow-sm" style="max-height: 150px; max-width: 100%; object-fit: cover;" alt="Preview" />
                                    </div>
                                @endif

                                @error('image')
                                    <div class="text-danger mt-2 fs-7">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-xl-3"></div>
                            <div class="col-xl-9">
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="send">
                                        <i class="ki-outline ki-send fs-2 me-2"></i> {{ trans('global.notification_page.send_notification_now') }}
                                    </span>
                                    <span wire:loading wire:target="send">
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span> {{ trans('global.notification_page.sending_to_app') }}
                                    </span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
