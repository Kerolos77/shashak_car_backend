<?php

namespace App\Http\Livewire\VehicleType;

use App\Models\VehicleType;
use Livewire\Component;

class Create extends Component
{
    public VehicleType $vehicleType;

    public function mount(VehicleType $vehicleType)
    {
        $this->vehicleType         = $vehicleType;
        $this->vehicleType->enable = false;
    }

    public function render()
    {
        return view('livewire.vehicle-type.create');
    }

    public function submit()
    {
        $this->validate();

        $this->vehicleType->save();

        return redirect()->route('admin.vehicle-types.index');
    }

    protected function rules(): array
    {
        return [
            'vehicleType.enable' => [
                'boolean',
            ],
            'vehicleType.name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
