@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> معاملات المحفظة </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
@section('title', $pageTitle)
@section('pageName', $pageTitle)
@push('styles')
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('/') }}assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endpush
@push('scripts')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/js/tables-datatables-basic.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script>
    $(function() {
        var select2 = $('.select2');
        if (select2.length) {
            select2.each(function () {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Select value',
                    dropdownParent: $this.parent()
                });
            });
        }
    })
</script>
@endpush

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.add_amount') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label" for="amount">{{ __('global.email') }}</label>
        <input type="number" step="0.5" id="amount" name="amount" class="form-control" placeholder="{{ __('global.amount') }}" />
      </div>
      <div class="mb-3">
        <label class="form-label" for="user_id">{{ __('global.user') }}</label>
        <select name="user_id" class="select2 form-select">
          <option value="">{{ __('global.user') }}</option>
          @foreach ($users as $user)
          <option value="{{$user->id}}">{{$user->email}}</option>
          @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">No records found.</div>
                                </td>
                            </tr>
                        @endforelse
        </select>
      </div>
      <div class="mb-0">
        <button class="btn btn-primary" type="submit">
          <i class="ti ti-plus me-1"></i>
          <span class="align-middle">{{ __('global.add') }}</span>
        </button>
      </div>
    </form>
  </div>
</div>
<br>
<div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="datatables-basic table">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">{{ __('global.amount') }}</th>
            <th class="text-center">{{ __('global.type') }}</th>
            <th class="text-center">{{ __('global.order') }}</th>
            <th class="text-center">{{ __('global.driver') }}</th>
            <th class="text-center">{{ __('global.user') }}</th>
            <th class="text-center">{{ __('global.created_at') }}</th>
          </tr>
        </thead>
        <tbody>
            @forelse ($rows ?? [] as $item)
            <tr>
                <td class="text-center">{{ $item->id }}</td>
                <td class="text-center">{{ number_format($item->amount, 2) }} EGP</td>
                <td class="text-center">{{ $item->type }}</td>
                <td class="text-center">
                    @if ($item->order != null)
                        {{ $item->order->id }}
                    @else
                    <i class="ti ti-circle-check text-danger"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($item->driver != null)
                        {{ $item->driver->email }}
                    @else
                    <i class="ti ti-circle-check text-danger"></i>
                    @endif
                </td>
                <td class="text-center">
                    @if ($item->user != null)
                        {{ $item->user->email }}
                    @else
                    <i class="ti ti-circle-check text-danger"></i>
                    @endif
                </td>
                <td class="text-center">
                   {{$item->created_at}}
                </td>
            </tr>
            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">No records found.</div>
                                </td>
                            </tr>
                        @endforelse

           
        </tbody>
      </table>
    </div>
  </div>
@endsection