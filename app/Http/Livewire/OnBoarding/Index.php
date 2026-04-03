<?php

namespace App\Http\Livewire\OnBoarding;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\OnBoarding;
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
        $this->orderable         = (new OnBoarding())->orderable;
    }

    public function render()
    {
        $query = OnBoarding::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $onBoardings = $query->paginate($this->perPage);

        return view('livewire.on-boarding.index', compact('onBoardings', 'query'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('on_boarding_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        OnBoarding::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function delete(OnBoarding $onBoarding)
    {
        abort_if(Gate::denies('on_boarding_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $onBoarding->delete();
    }
}
