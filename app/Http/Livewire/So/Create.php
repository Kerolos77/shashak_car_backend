<?php

namespace App\Http\Livewire\So;

use App\Models\Order;
use App\Models\So;
use Livewire\Component;

class Create extends Component
{
    public So $so;

    public array $listsForFields = [];

    public function mount(So $so)
    {
        $this->so = $so;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.so.create');
    }

    public function submit()
    {
        $this->validate();

        $this->so->save();

        return redirect()->route('admin.sos.index');
    }

    protected function rules(): array
    {
        return [
            'so.order_id' => [
                'integer',
                'exists:orders,id',
                'nullable',
            ],
            'so.order_type' => [
                'string',
                'nullable',
            ],
            'so.status' => [
                'string',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['order'] = Order::pluck('source_location_name', 'id')->toArray();
    }
}
