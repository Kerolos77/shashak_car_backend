@extends('layouts.admin')

@section('title', __('global.edit') . ' ' . __('cruds.role.title_singular'))

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted"> الأدوار والصلاحيات </li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">تعديل دور</li>
@endsection

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <form action="{{ route('admin.roles.update', $role) }}" method="POST" id="roleForm">
            @csrf
            @method('PUT')

            <!-- Header Card -->
            <div class="card mb-6 shadow-sm border-0">
                <div class="card-body p-6 d-flex align-items-center justify-content-between flex-wrap gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="bg-light-primary p-4 rounded-circle">
                            <i class="ki-outline ki-shield-tick fs-2x text-primary"></i>
                        </div>
                        <div>
                            <h2 class="fw-bolder mb-1">تعديل الدور الوظيفي: {{ $role->title }}</h2>
                            <div class="text-muted fs-6">قم بتحديث اسم الدور والصلاحيات الممنوحة له في النظام.</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-light-primary btn-sm fw-bold" id="btnSelectAllGlobal">
                            <i class="ki-outline ki-check-square me-1"></i> تحديد جميع صلاحيات النظام
                        </button>
                        <button type="button" class="btn btn-light-danger btn-sm fw-bold" id="btnDeselectAllGlobal">
                            <i class="ki-outline ki-cross-square me-1"></i> إلغاء تحديد الكل
                        </button>
                    </div>
                </div>
            </div>

            <!-- Role Title Card -->
            <div class="card mb-6 shadow-sm border-0">
                <div class="card-body p-6">
                    <div class="mb-2">
                        <label class="form-label fw-bolder fs-5 text-dark required">اسم الدور / المسمى الوظيفي (Role Title)</label>
                        <input type="text" 
                               name="title" 
                               class="form-control form-control-solid form-control-lg @error('title') is-invalid @enderror" 
                               placeholder="مثال: مشرف التسويق، محصل حسابات، دعم فني..." 
                               value="{{ old('title', $role->title) }}" 
                               required>
                        @error('title')
                            <div class="invalid-feedback d-block mt-2">
                                <i class="ki-outline ki-information-2 me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summary Bar -->
            <div class="card mb-6 bg-light-info border border-info border-opacity-25 shadow-sm">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ki-outline ki-information-2 fs-2 text-info me-1"></i>
                        <span class="fw-bold text-dark fs-6">💡 حرك الماوس فوق أي صلاحية لرؤية شرحها الوظيفي بالتفصيل باللغة العربية والإنجليزية.</span>
                    </div>
                    <div>
                        <span class="badge bg-info text-white fs-6 px-3 py-2 fw-bold" id="globalCounter">
                            مجموع الصلاحيات المختارة: <span id="selectedCount">0</span> من أصل {{ $permissions->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Permission Groups Grid -->
            <div class="row g-6 mb-6">
                @php
                    $rolePermissionIds = old('permissions', $role->permissions->pluck('id')->toArray());
                @endphp

                @foreach($permissionGroups as $groupKey => $group)
                    <div class="col-12 col-xl-6">
                        <div class="card shadow-sm border-0 h-100 perm-group-card">
                            <div class="card-header border-0 pt-5 pb-3 d-flex align-items-center justify-content-between">
                                <div class="card-title m-0 d-flex align-items-center gap-2">
                                    <i class="ki-outline {{ $group['icon'] }} fs-2 text-primary"></i>
                                    <h4 class="fw-bolder m-0 text-dark">{{ $group['name_ar'] }}</h4>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light-primary text-primary fw-bold group-count-badge">
                                        <span class="group-selected-count">0</span> / {{ count($group['permissions']) }}
                                    </span>
                                    <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-toggle-group" title="تحديد / إلغاء تحديد القسم">
                                        <i class="ki-outline ki-check fs-4"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-2 pb-5">
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($group['permissions'] as $perm)
                                        @php
                                            $isSelected = in_array($perm['id'], $rolePermissionIds);
                                            $tooltipHtml = "<div class='text-start p-2' style='max-width:250px;'>
                                                <div class='fw-bolder text-warning mb-1'>{$perm['label_ar']}</div>
                                                <div class='fs-8 text-white mb-2'>{$perm['desc_ar']}</div>
                                                <hr class='my-1 border-secondary'>
                                                <div class='fs-9 text-white-50'>{$perm['desc_en']}</div>
                                            </div>";
                                        @endphp
                                        
                                        <div class="perm-pill-wrapper position-relative" 
                                             data-bs-toggle="tooltip" 
                                             data-bs-html="true" 
                                             data-bs-placement="top"
                                             title="{!! htmlspecialchars($tooltipHtml) !!}">
                                            
                                            <input type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $perm['id'] }}" 
                                                   id="perm_{{ $perm['id'] }}" 
                                                   class="d-none perm-checkbox" 
                                                   {{ $isSelected ? 'checked' : '' }} />

                                            <label for="perm_{{ $perm['id'] }}" 
                                                   class="btn btn-sm {{ $isSelected ? 'btn-primary text-white shadow-sm active-pill' : 'btn-outline btn-outline-dashed btn-outline-default text-dark' }} perm-label fw-bold py-2 px-3 m-0 transition-all cursor-pointer">
                                                <i class="ki-outline ki-check fs-6 me-1 {{ $isSelected ? '' : 'd-none' }} perm-check-icon"></i>
                                                <span>{{ $perm['label_ar'] }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Submit Buttons Footer -->
            <div class="card shadow-sm border-0 mb-8">
                <div class="card-body p-5 d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light fw-bold">
                        {{ trans('global.cancel') }}
                    </a>
                    <button class="btn btn-primary fw-bolder px-8" type="submit">
                        <i class="ki-outline ki-check-circle fs-3 me-1"></i> حفظ التحديثات
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Recalculate counts and update UI state
    function updatePermissionCounts() {
        let totalSelected = 0;

        $('.perm-group-card').each(function() {
            const $card = $(this);
            const totalInGroup = $card.find('.perm-checkbox').length;
            const selectedInGroup = $card.find('.perm-checkbox:checked').length;
            totalSelected += selectedInGroup;

            $card.find('.group-selected-count').text(selectedInGroup);

            if (selectedInGroup === totalInGroup && totalInGroup > 0) {
                $card.find('.group-count-badge').removeClass('bg-light-primary text-primary').addClass('bg-success text-white');
            } else if (selectedInGroup > 0) {
                $card.find('.group-count-badge').removeClass('bg-success text-white').addClass('bg-light-primary text-primary');
            } else {
                $card.find('.group-count-badge').removeClass('bg-success text-white bg-light-primary text-primary').addClass('bg-light-secondary text-dark');
            }
        });

        $('#selectedCount').text(totalSelected);
    }

    // Toggle Permission Checkbox Styling
    $(document).on('change', '.perm-checkbox', function() {
        const $checkbox = $(this);
        const $label = $checkbox.siblings('.perm-label');
        const $icon = $label.find('.perm-check-icon');

        if ($checkbox.is(':checked')) {
            $label.removeClass('btn-outline btn-outline-dashed btn-outline-default text-dark')
                  .addClass('btn-primary text-white shadow-sm active-pill');
            $icon.removeClass('d-none');
        } else {
            $label.removeClass('btn-primary text-white shadow-sm active-pill')
                  .addClass('btn-outline btn-outline-dashed btn-outline-default text-dark');
            $icon.addClass('d-none');
        }

        updatePermissionCounts();
    });

    // Toggle All Permissions in a Specific Group Card
    $('.btn-toggle-group').on('click', function() {
        const $card = $(this).closest('.perm-group-card');
        const $checkboxes = $card.find('.perm-checkbox');
        const total = $checkboxes.length;
        const checked = $card.find('.perm-checkbox:checked').length;

        const shouldCheckAll = checked < total;

        $checkboxes.each(function() {
            $(this).prop('checked', shouldCheckAll).trigger('change');
        });
    });

    // Global Select All
    $('#btnSelectAllGlobal').on('click', function() {
        $('.perm-checkbox').prop('checked', true).trigger('change');
    });

    // Global Deselect All
    $('#btnDeselectAllGlobal').on('click', function() {
        $('.perm-checkbox').prop('checked', false).trigger('change');
    });

    // Initial count run
    updatePermissionCounts();
});
</script>
@endpush