<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('review_customer_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="ReviewCustomer" format="csv" />
                <livewire:excel-export model="ReviewCustomer" format="xlsx" />
                <livewire:excel-export model="ReviewCustomer" format="pdf" />
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
                            {{ trans('cruds.reviewCustomer.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.comment') }}
                            @include('components.table.sort', ['field' => 'comment'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.customer') }}
                            @include('components.table.sort', ['field' => 'customer.name'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.driver') }}
                            @include('components.table.sort', ['field' => 'driver.name'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.date') }}
                            @include('components.table.sort', ['field' => 'date'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.rating') }}
                            @include('components.table.sort', ['field' => 'rating'])
                        </th>
                        <th>
                            {{ trans('cruds.reviewCustomer.fields.type') }}
                            @include('components.table.sort', ['field' => 'type'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewCustomers as $reviewCustomer)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $reviewCustomer->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $reviewCustomer->id }}
                            </td>
                            <td>
                                {{ $reviewCustomer->comment }}
                            </td>
                            <td>
                                @if($reviewCustomer->customer)
                                    <span class="badge badge-relationship">{{ $reviewCustomer->customer->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($reviewCustomer->driver)
                                    <span class="badge badge-relationship">{{ $reviewCustomer->driver->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $reviewCustomer->date }}
                            </td>
                            <td>
                                {{ $reviewCustomer->rating }}
                            </td>
                            <td>
                                {{ $reviewCustomer->type }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('review_customer_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.review-customers.show', $reviewCustomer) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('review_customer_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.review-customers.edit', $reviewCustomer) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('review_customer_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $reviewCustomer->id }})" wire:loading.attr="disabled">
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
            {{ $reviewCustomers->links() }}
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