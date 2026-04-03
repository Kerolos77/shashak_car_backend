<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('thread_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="Thread" format="csv" />
                <livewire:excel-export model="Thread" format="xlsx" />
                <livewire:excel-export model="Thread" format="pdf" />
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
                            {{ trans('cruds.thread.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.message') }}
                            @include('components.table.sort', ['field' => 'message'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.message_type') }}
                            @include('components.table.sort', ['field' => 'message_type'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.order') }}
                            @include('components.table.sort', ['field' => 'order.accepted_driver'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.receiver') }}
                            @include('components.table.sort', ['field' => 'receiver.name'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.sender') }}
                            @include('components.table.sort', ['field' => 'sender.name'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.url') }}
                            @include('components.table.sort', ['field' => 'url'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.video_thumbnail') }}
                            @include('components.table.sort', ['field' => 'video_thumbnail'])
                        </th>
                        <th>
                            {{ trans('cruds.thread.fields.chat') }}
                            @include('components.table.sort', ['field' => 'chat.customer_name'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($threads as $thread)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $thread->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $thread->id }}
                            </td>
                            <td>
                                {{ $thread->message }}
                            </td>
                            <td>
                                {{ $thread->message_type }}
                            </td>
                            <td>
                                @if($thread->order)
                                    <span class="badge badge-relationship">{{ $thread->order->accepted_driver ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($thread->receiver)
                                    <span class="badge badge-relationship">{{ $thread->receiver->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($thread->sender)
                                    <span class="badge badge-relationship">{{ $thread->sender->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $thread->url }}
                            </td>
                            <td>
                                {{ $thread->video_thumbnail }}
                            </td>
                            <td>
                                @if($thread->chat)
                                    <span class="badge badge-relationship">{{ $thread->chat->customer_name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('thread_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.threads.show', $thread) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('thread_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.threads.edit', $thread) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('thread_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $thread->id }})" wire:loading.attr="disabled">
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
            {{ $threads->links() }}
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