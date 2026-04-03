<?php

namespace App\Http\Livewire\FreightVehicle;

use App\Models\FreightVehicle;
use Livewire\Component;

class Create extends Component
{
    public FreightVehicle $freightVehicle;

    public function mount(FreightVehicle $freightVehicle)
    {
        $this->freightVehicle         = $freightVehicle;
        $this->freightVehicle->enable = false;
    }

    public function render()
    {
        return view('livewire.freight-vehicle.create');
    }

    public function submit()
    {
        $this->validate();

        $this->freightVehicle->save();

        return redirect()->route('admin.freight-vehicles.index');
    }

    protected function rules(): array
    {
        return [
            'freightVehicle.description' => [
                'string',
                'nullable',
            ],
            'freightVehicle.enable' => [
                'boolean',
            ],
            'freightVehicle.height' => [
                'string',
                'nullable',
            ],
            'freightVehicle.image' => [
                'string',
                'nullable',
            ],
            'freightVehicle.km_charge' => [
                'string',
                'nullable',
            ],
            'freightVehicle.length' => [
                'string',
                'nullable',
            ],
            'freightVehicle.name' => [
                'string',
                'nullable',
            ],
            'freightVehicle.width' => [
                'string',
                'nullable',
            ],
        ];
    }
}
