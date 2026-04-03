<?php

namespace App\Http\Livewire\ReviewCustomer;

use App\Models\ReviewCustomer;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public array $listsForFields = [];

    public ReviewCustomer $reviewCustomer;

    public function mount(ReviewCustomer $reviewCustomer)
    {
        $this->reviewCustomer = $reviewCustomer;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.review-customer.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->reviewCustomer->save();

        return redirect()->route('admin.review-customers.index');
    }

    protected function rules(): array
    {
        return [
            'reviewCustomer.comment' => [
                'string',
                'nullable',
            ],
            'reviewCustomer.customer_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'reviewCustomer.driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'reviewCustomer.date' => [
                'string',
                'nullable',
            ],
            'reviewCustomer.rating' => [
                'string',
                'nullable',
            ],
            'reviewCustomer.type' => [
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
