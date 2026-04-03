<div>
    <div class="card-controls sm:flex">
        <div class="w-full sm:w-1/2">
            Per page:
            <select wire:model="perPage" class="form-select w-full sm:w-1/6">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('wallet_transaction_delete')
                <button class="btn btn-rose ml-3 disabled:opacity-50 disabled:cursor-not-allowed" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    {{ __('Delete Selected') }}
                </button>
            @endcan

            @if(file_exists(app_path('Http/Livewire/ExcelExport.php')))
                <livewire:excel-export model="WalletTransaction" format="csv" />
                <livewire:excel-export model="WalletTransaction" format="xlsx" />
                <livewire:excel-export model="WalletTransaction" format="pdf" />
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
                            {{ trans('cruds.walletTransaction.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.amount') }}
                            @include('components.table.sort', ['field' => 'amount'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.note') }}
                            @include('components.table.sort', ['field' => 'note'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.order_type') }}
                            @include('components.table.sort', ['field' => 'order_type'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.payment_type') }}
                            @include('components.table.sort', ['field' => 'payment_type'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.transaction') }}
                            @include('components.table.sort', ['field' => 'transaction'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.user') }}
                            @include('components.table.sort', ['field' => 'user.name'])
                        </th>
                        <th>
                            {{ trans('cruds.walletTransaction.fields.user_type') }}
                            @include('components.table.sort', ['field' => 'user_type'])
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($walletTransactions as $walletTransaction)
                        <tr>
                            <td>
                                <input type="checkbox" value="{{ $walletTransaction->id }}" wire:model="selected">
                            </td>
                            <td>
                                {{ $walletTransaction->id }}
                            </td>
                            <td>
                                {{ $walletTransaction->amount }}
                            </td>
                            <td>
                                {{ $walletTransaction->note }}
                            </td>
                            <td>
                                {{ $walletTransaction->order_type }}
                            </td>
                            <td>
                                {{ $walletTransaction->payment_type }}
                            </td>
                            <td>
                                {{ $walletTransaction->transaction }}
                            </td>
                            <td>
                                @if($walletTransaction->user)
                                    <span class="badge badge-relationship">{{ $walletTransaction->user->name ?? '' }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $walletTransaction->user_type }}
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    @can('wallet_transaction_show')
                                        <a class="btn btn-sm btn-info mr-2" href="{{ route('admin.wallet-transactions.show', $walletTransaction) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('wallet_transaction_edit')
                                        <a class="btn btn-sm btn-success mr-2" href="{{ route('admin.wallet-transactions.edit', $walletTransaction) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('wallet_transaction_delete')
                                        <button class="btn btn-sm btn-rose mr-2" type="button" wire:click="confirm('delete', {{ $walletTransaction->id }})" wire:loading.attr="disabled">
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
            {{ $walletTransactions->links() }}
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