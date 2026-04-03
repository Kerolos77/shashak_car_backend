<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('order_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Order" format="csv" />
                <livewire:excel-export model="Order" format="xlsx" />
                <livewire:excel-export model="Order" format="pdf" />
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
                            {{ trans('cruds.order.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.accepted_driver') }}
                            @include('components.table.sort', ['field' => 'accepted_driver'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.admin_commission') }}
                            @include('components.table.sort', ['field' => 'admin_commission'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.destination_location_name') }}
                            @include('components.table.sort', ['field' => 'destination_location_name'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.destination_location_l_at_lng') }}
                            @include('components.table.sort', ['field' => 'destination_location_l_at_lng'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.distance') }}
                            @include('components.table.sort', ['field' => 'distance'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.distance_type') }}
                            @include('components.table.sort', ['field' => 'distance_type'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.driver') }}
                            @include('components.table.sort', ['field' => 'driver'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.final_rate') }}
                            @include('components.table.sort', ['field' => 'final_rate'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.offer_rate') }}
                            @include('components.table.sort', ['field' => 'offer_rate'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.otp') }}
                            @include('components.table.sort', ['field' => 'otp'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.payment_status') }}
                            @include('components.table.sort', ['field' => 'payment_status'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.payment_type') }}
                            @include('components.table.sort', ['field' => 'payment_type'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.position') }}
                            @include('components.table.sort', ['field' => 'position'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.rejected_driver') }}
                            @include('components.table.sort', ['field' => 'rejected_driver'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.service') }}
                            @include('components.table.sort', ['field' => 'service'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.source_location_l_at_lng') }}
                            @include('components.table.sort', ['field' => 'source_location_l_at_lng'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.source_location_name') }}
                            @include('components.table.sort', ['field' => 'source_location_name'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.status') }}
                            @include('components.table.sort', ['field' => 'status'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.tax_list') }}
                            @include('components.table.sort', ['field' => 'tax_list'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.update_date') }}
                            @include('components.table.sort', ['field' => 'update_date'])
                        </th>
                        <th>
                            {{ trans('cruds.order.fields.user') }}
                            @include('components.table.sort', ['field' => 'user.name'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $order->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $order->id }}
                            </td>
                            <td>
                                {{ $order->accepted_driver }}
                            </td>
                            <td>
                                {{ $order->admin_commission }}
                            </td>
                            <td>
                                {{ $order->destination_location_name }}
                            </td>
                            <td>
                                {{ $order->destination_location_l_at_lng }}
                            </td>
                            <td>
                                {{ $order->distance }}
                            </td>
                            <td>
                                {{ $order->distance_type }}
                            </td>
                            <td>
                                {{ $order->driver }}
                            </td>
                            <td>
                                {{ $order->final_rate }}
                            </td>
                            <td>
                                {{ $order->offer_rate }}
                            </td>
                            <td>
                                {{ $order->otp }}
                            </td>
                            <td>
                                <input class="disabled:opacity-50 disabled:cursor-not-allowed" type="checkbox" disabled {{ $order->payment_status ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $order->payment_type }}
                            </td>
                            <td>
                                {{ $order->position }}
                            </td>
                            <td>
                                {{ $order->rejected_driver }}
                            </td>
                            <td>
                                {{ $order->service }}
                            </td>
                            <td>
                                {{ $order->source_location_l_at_lng }}
                            </td>
                            <td>
                                {{ $order->source_location_name }}
                            </td>
                            <td>
                                {{ $order->status }}
                            </td>
                            <td>
                                {{ $order->tax_list }}
                            </td>
                            <td>
                                {{ $order->update_date }}
                            </td>
                            <td>
                                @if($order->user)
                                    <span class="badge badge-relationship">{{ $order->user->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('order_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.orders.show', $order) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('order_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.orders.edit', $order) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('order_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $order->id }})" wire:loading.attr="disabled">
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
            {{ $orders->links() }}
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