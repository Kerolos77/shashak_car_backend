<?php

namespace App\Http\Livewire\ReviewDriver;

use App\Models\ReviewDriver;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public array $listsForFields = [];

    public ReviewDriver $reviewDriver;

    public function mount(ReviewDriver $reviewDriver)
    {
        $this->reviewDriver = $reviewDriver;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.review-driver.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->reviewDriver->save();

        return redirect()->route('admin.review-drivers.index');
    }

    protected function rules(): array
    {
        return [
            'reviewDriver.comment' => [
                'string',
                'nullable',
            ],
            'reviewDriver.customer_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'reviewDriver.driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'reviewDriver.rating' => [
                'string',
                'nullable',
            ],
            'reviewDriver.type' => [
                'string',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customer'] = User::pluck('name', 'id')->toArray();
        $this->listsForFields['driver']   = User::pluck('name', 'id')->toArray();
    }
}
