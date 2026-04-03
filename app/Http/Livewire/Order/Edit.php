<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Order $order;

    public array $listsForFields = [];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.order.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->order->save();

        return redirect()->route('admin.orders.index');
    }

    protected function rules(): array
    {
        return [
            'order.accepted_driver' => [
                'string',
                'nullable',
            ],
            'order.admin_commission' => [
                'string',
                'nullable',
            ],
            'order.destination_location_name' => [
                'string',
                'nullable',
            ],
            'order.destination_location_l_at_lng' => [
                'string',
                'nullable',
            ],
            'order.distance' => [
                'string',
                'nullable',
            ],
            'order.distance_type' => [
                'string',
                'nullable',
            ],
            'order.driver' => [
                'string',
                'nullable',
            ],
            'order.final_rate' => [
                'string',
                'nullable',
            ],
            'order.offer_rate' => [
                'string',
                'nullable',
            ],
            'order.otp' => [
                'string',
                'nullable',
            ],
            'order.payment_status' => [
                'boolean',
            ],
            'order.payment_type' => [
                'string',
                'nullable',
            ],
            'order.position' => [
                'string',
                'nullable',
            ],
            'order.rejected_driver' => [
                'string',
                'nullable',
            ],
            'order.service' => [
                'string',
                'nullable',
            ],
            'order.source_location_l_at_lng' => [
                'string',
                'nullable',
            ],
            'order.source_location_name' => [
                'string',
                'nullable',
            ],
            'order.status' => [
                'string',
                'nullable',
            ],
            'order.tax_list' => [
                'string',
                'nullable',
            ],
            'order.update_date' => [
                'string',
                'nullable',
            ],
            'order.user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['user'] = User::pluck('name', 'id')->toArray();
    }
}
