<div class="card">
    <div class="card-header border-0">
     
        
        
      
    </div>

    <div class="card-body pt-0">
        

        <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="">
                <thead class="text-muted bg-light">
                    <tr>
                        <th width="50">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="selectAll">
                            </div>
                        </th>
                        <th>
                            {{ trans('cruds.faq.fields.id') }}
                            @include('components.table.sort', ['field' => 'id'])
                        </th>
                        <th>
                            {{ trans('cruds.faq.fields.title') }}
                            @include('components.table.sort', ['field' => 'title'])
                        </th>
                        <th>
                            {{ trans('cruds.faq.fields.description') }}
                            @include('components.table.sort', ['field' => 'description'])
                        </th>
                        <th>
                            {{ trans('cruds.faq.fields.enable') }}
                            @include('components.table.sort', ['field' => 'enable'])
                        </th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $faq->id }}" wire:model="selected">
                            </div>
                        </td>
                        <td>{{ $faq->id }}</td>
                        <td>{{ $faq->title }}</td>
                        <td>{{ Str::limit($faq->description, 50) }}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" disabled {{ $faq->enable ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                @can('faq_show')
                                <a href="{{ route('admin.faqs.show', $faq) }}" class="btn btn-light" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                @can('faq_edit')
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-light" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('faq_delete')
                                <button 
                                    class="btn btn-light" 
                                    type="button" 
                                    wire:click="confirm('delete', {{ $faq->id }})" 
                                    wire:loading.attr="disabled"
                                    title="Delete"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-info-circle me-2"></i> No FAQs found
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
        @if($this->selectedCount)
        <div class="text-muted">
            {{ $this->selectedCount }} selected
        </div>
        @else
        <div></div>
        @endif
        
        <div>
            {{ $faqs->links() }}
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