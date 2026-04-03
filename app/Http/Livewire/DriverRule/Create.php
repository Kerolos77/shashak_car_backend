<?php

namespace App\Http\Livewire\DriverRule;

use App\Models\DriverRule;
use Livewire\Component;

class Create extends Component
{
    public DriverRule $driverRule;

    public function mount(DriverRule $driverRule)
    {
        $this->driverRule             = $driverRule;
        $this->driverRule->enable     = false;
        $this->driverRule->is_deleted = false;
    }

    public function render()
    {
        return view('livewire.driver-rule.create');
    }

    public function submit()
    {
        $this->validate();

        $this->driverRule->save();

        return redirect()->route('admin.driver-rules.index');
    }

    protected function rules(): array
    {
        return [
            'driverRule.enable' => [
                'boolean',
            ],
            'driverRule.image' => [
                'string',
                'nullable',
            ],
            'driverRule.is_deleted' => [
                'boolean',
            ],
            'driverRule.name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
