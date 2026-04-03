<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('driver_user_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="DriverUser" format="csv" />
                <livewire:excel-export model="DriverUser" format="xlsx" />
                <livewire:excel-export model="DriverUser" format="pdf" />
            @endif




        </div>
        <div class="w-full sm:w-1/2 sm:text-right">
            Search:
            <input type="text" wire:model.debounce.300ms="search" class="w-full sm:w-1/3 inline-block" />
        </div>
    </div>
    <div wire:loading.delay>
        Loading...
    </div>

    <div class="overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table table-index w-full">
                <thead>
                    <tr>
                        <th class="w-9">
                        </th>
                        <th class="w-28">
                            {{ trans('cruds.driverUser.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.country_code') }}
                            @include('components.table.sort', ['field' => 'country_code'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.document_verification') }}
                            @include('components.table.sort', ['field' => 'document_verification'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.email') }}
                            @include('components.table.sort', ['field' => 'email'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.fcm_token') }}
                            @include('components.table.sort', ['field' => 'fcm_token'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.full_name') }}
                            @include('components.table.sort', ['field' => 'full_name'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.is_online') }}
                            @include('components.table.sort', ['field' => 'is_online'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.login_type') }}
                            @include('components.table.sort', ['field' => 'login_type'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.phone_number') }}
                            @include('components.table.sort', ['field' => 'phone_number'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.profile_pic') }}
                            @include('components.table.sort', ['field' => 'profile_pic'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.reviews_count') }}
                            @include('components.table.sort', ['field' => 'reviews_count'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.reviews_sum') }}
                            @include('components.table.sort', ['field' => 'reviews_sum'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.rotation') }}
                            @include('components.table.sort', ['field' => 'rotation'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.service') }}
                            @include('components.table.sort', ['field' => 'service'])
                        </th>
                        <th>
                            {{ trans('cruds.driverUser.fields.wallet_amount') }}
                            @include('components.table.sort', ['field' => 'wallet_amount'])
                        </th>
                        <th>
                            {{ __('Referral Code') }}
                        </th>
                        <th>
                            {{ __('Referred By') }}
                        </th>
                        <th>
                            {{ __('Gender') }}
                        </th>
                        <th>
                            {{ __('Created At') }}
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($driverUsers as $driverUser)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $driverUser->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $driverUser->id }}
                            </td>
                            <td>
                                {{ $driverUser->country_code }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $driverUser->document_verification ? 'checked' : '' }}>
                            </td>
                            <td>
                                <a class="link-light-blue" href="mailto:{{ $driverUser->email }}">
                                    <i class="far fa-envelope fa-fw">
                                    </i>
                                    {{ $driverUser->email }}
                                </a>
                            </td>
                            <td>
                                {{ $driverUser->fcm_token }}
                            </td>
                            <td>
                                {{ $driverUser->full_name }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $driverUser->is_online ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $driverUser->login_type }}
                            </td>
                            <td>
                                {{ $driverUser->phone_number }}
                            </td>
                            <td>
                                {{ $driverUser->profile_pic }}
                            </td>
                            <td>
                                {{ $driverUser->reviews_count }}
                            </td>
                            <td>
                                {{ $driverUser->reviews_sum }}
                            </td>
                            <td>
                                {{ $driverUser->rotation }}
                            </td>
                            <td>
                                {{ $driverUser->service }}
                            </td>
                            <td>
                                {{ $driverUser->wallet_amount }}
                            </td>
                            <td>
                                {{ $driverUser->referral_code }}
                            </td>
                            <td>
                                {{ $driverUser->referrer ? $driverUser->referrer->name : '-' }}
                            </td>
                            <td>
                                {{ $driverUser->gender }}
                            </td>
                            <td>
                                {{ $driverUser->created_at }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('driver_user_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.driver-users.show', $driverUser) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('driver_user_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.driver-users.edit', $driverUser) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('driver_user_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $driverUser->id }})" wire:loading.attr="disabled">
                                            {{ trans('global.delete') }}
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-body">
        <div class="pt-3">
            @if($this->selectedCount)
                <p class="text-sm leading-5">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
            {{ $driverUsers->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('confirm', e => {
    if (!confirm("{{ trans('global.areYouSure') }}")) {
        return
    }
@this[e.callback](...e.argv)
})
    </script>
@endpush