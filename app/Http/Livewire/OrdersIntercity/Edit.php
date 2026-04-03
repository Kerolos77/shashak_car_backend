<?php

namespace App\Http\Livewire\OrdersIntercity;

use App\Models\IntercityService;
use App\Models\OrdersIntercity;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public array $listsForFields = [];

    public OrdersIntercity $ordersIntercity;

    public function mount(OrdersIntercity $ordersIntercity)
    {
        $this->ordersIntercity = $ordersIntercity;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.orders-intercity.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->ordersIntercity->save();

        return redirect()->route('admin.orders-intercities.index');
    }

    protected function rules(): array
    {
        return [
            'ordersIntercity.accepted_driver' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.admin_commission' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.comments' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.destination_city' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.destination_location_lat_lng' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.destination_location_name' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.distance' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.distance_type' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'ordersIntercity.final_rate' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.intercity_service' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.intercityservice_id' => [
                'integer',
                'exists:intercity_services,id',
                'nullable',
            ],
            'ordersIntercity.number_of_passenger' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.offer_rate' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.otp' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.parcel_dimension' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.parcel_image' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.parcel_weight' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.payment_status' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.payment_type' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.position' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.rejected_driver' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.source_city' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.source_location_lat_lng' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.source_location_name' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.status' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.tax_list' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.user_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'ordersIntercity.when_dates' => [
                'string',
                'nullable',
            ],
            'ordersIntercity.when_time' => [
                'string',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['driver']           = User::pluck('name', 'id')->toArray();
        $this->listsForFields['intercityservice'] = IntercityService::pluck('name', 'id')->toArray();
        $this->listsForFields['user']             = User::pluck('name', 'id')->toArray();
    }
}
