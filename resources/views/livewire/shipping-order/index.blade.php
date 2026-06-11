<div>
    <div class="card-controls sm:flex mb-5">
        <div class="w-full sm:w-1/2 d-flex align-items-center gap-3">
            <span class="text-gray-600 fw-bold">Per page:</span>
            <select wire:model="perPage" class="form-select form-select-sm w-100px">
                @foreach($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @can('order_delete')
                <button class="btn btn-sm btn-light-danger disabled:opacity-50" type="button" wire:click="confirm('deleteSelected')" wire:loading.attr="disabled" {{ $this->selectedCount ? '' : 'disabled' }}>
                    <i class="ki-duotone ki-trash fs-2"></i> {{ __('Delete Selected') }}
                </button>
            @endcan
        </div>
        <div class="w-full sm:w-1/2 sm:text-right mt-3 mt-sm-0">
            <div class="position-relative d-inline-block w-100 w-sm-300px">
                <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4"></i>
                <input type="text" wire:model.debounce.300ms="search" class="form-control form-control-sm ps-12" placeholder="Search shipping orders..." />
            </div>
        </div>
    </div>
    
    <div wire:loading.delay class="text-primary fw-bold mb-3">
        Loading data...
    </div>

    <div class="table-responsive">
        <table class="table table-row-dashed align-middle gs-0 gy-4">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2"></th>
                    <th>ID @include('components.table.sort', ['field' => 'id'])</th>
                    <th>Sender @include('components.table.sort', ['field' => 'user.name'])</th>
                    <th>Receiver</th>
                    <th>Driver @include('components.table.sort', ['field' => 'driver'])</th>
                    <th>Service @include('components.table.sort', ['field' => 'service'])</th>
                    <th>Rate @include('components.table.sort', ['field' => 'final_rate'])</th>
                    <th>Status @include('components.table.sort', ['field' => 'status'])</th>
                    <th>Payment @include('components.table.sort', ['field' => 'payment_status'])</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="{{ $order->id }}" wire:model="selected">
                            </div>
                        </td>
                        <td><span class="text-gray-800 fw-bold">#{{ $order->id }}</span></td>
                        <td>
                            @if($order->user)
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-35px me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold">{{ substr($order->user->name, 0, 1) }}</div>
                                    </div>
                                    <span class="text-gray-800 fw-bold text-hover-primary mb-1">{{ $order->user->name }}</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="text-gray-800 fw-bold">{{ $order->receiver_name ?? 'N/A' }}</span>
                                <span class="text-muted fs-8">{{ $order->receiver_phone ?? '' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($order->driver_id)
                                <span class="badge badge-light-info fw-bold">{{ $order->driver ?? 'Driver' }}</span>
                            @else
                                <span class="text-muted fs-8">Not assigned</span>
                            @endif
                        </td>
                        <td><span class="text-gray-600 fw-semibold">{{ $order->service }}</span></td>
                        <td>
                            <span class="text-success fw-bold">{{ $order->final_rate }} EGP</span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'assigned' => 'primary',
                                    'driver_on_a_way' => 'primary',
                                    'on_trip' => 'info',
                                    'completed' => 'success',
                                    'canceled' => 'danger'
                                ];
                                $color = $statusColors[$order->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-light-{{ $color }} fw-bold px-3 py-2">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $order->payment_status == 'paid' ? 'light-success' : 'light-warning' }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('order_show')
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                    <i class="ki-duotone ki-eye fs-2"></i>
                                </a>
                            @endcan
                            @can('order_delete')
                                <button type="button" wire:click="confirm('delete', {{ $order->id }})" wire:loading.attr="disabled" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                    <i class="ki-duotone ki-trash fs-2"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">No shipping orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="row align-items-center mt-5">
        <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
            @if($this->selectedCount)
                <span class="text-muted">
                    <span class="fw-bold">{{ $this->selectedCount }}</span> {{ __('Entries selected') }}
                </span>
            @endif
        </div>
        <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
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
