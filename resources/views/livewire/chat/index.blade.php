<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('chat_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Chat" format="csv" />
                <livewire:excel-export model="Chat" format="xlsx" />
                <livewire:excel-export model="Chat" format="pdf" />
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
                            {{ trans('cruds.chat.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.customer') }}
                            @include('components.table.sort', ['field' => 'customer.name'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.customer_name') }}
                            @include('components.table.sort', ['field' => 'customer_name'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.customer_profile_image') }}
                            @include('components.table.sort', ['field' => 'customer_profile_image'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.driver') }}
                            @include('components.table.sort', ['field' => 'driver.name'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.driver_name') }}
                            @include('components.table.sort', ['field' => 'driver_name'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.driver_profile_image') }}
                            @include('components.table.sort', ['field' => 'driver_profile_image'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.last_message') }}
                            @include('components.table.sort', ['field' => 'last_message'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.last_sender') }}
                            @include('components.table.sort', ['field' => 'last_sender'])
                        </th>
                        <th>
                            {{ trans('cruds.chat.fields.order') }}
                            @include('components.table.sort', ['field' => 'order.accepted_driver'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chats as $chat)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $chat->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $chat->id }}
                            </td>
                            <td>
                                @if($chat->customer)
                                    <span class="badge badge-relationship">{{ $chat->customer->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $chat->customer_name }}
                            </td>
                            <td>
                                {{ $chat->customer_profile_image }}
                            </td>
                            <td>
                                @if($chat->driver)
                                    <span class="badge badge-relationship">{{ $chat->driver->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $chat->driver_name }}
                            </td>
                            <td>
                                {{ $chat->driver_profile_image }}
                            </td>
                            <td>
                                {{ $chat->last_message }}
                            </td>
                            <td>
                                {{ $chat->last_sender }}
                            </td>
                            <td>
                                @if($chat->order)
                                    <span class="badge badge-relationship">{{ $chat->order->accepted_driver ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('chat_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.chats.show', $chat) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('chat_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.chats.edit', $chat) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('chat_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $chat->id }})" wire:loading.attr="disabled">
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
            {{ $chats->links() }}
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