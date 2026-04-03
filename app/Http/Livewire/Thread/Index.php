<?php

namespace App\Http\Livewire\Thread;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Thread;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, WithSorting, WithConfirmation;

    public int $perPage;

    public array $orderable;

    public string $search = '';

    public array $selected = [];

    public array $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = config('project.pagination.options');
        $this->orderable         = (new Thread())->orderable;
    }

    public function render()
    {
        $query = Thread::with(['order', 'receiver', 'sender', 'chat'])->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $threads = $query->paginate($this->perPage);

        return view('livewire.thread.index', compact('query', 'threads'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('thread_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Thread::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(Thread $thread)
    {
        abort_if(Gate::denies('thread_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $thread->delete();
    }
}
