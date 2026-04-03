<?php

namespace App\Http\Livewire\DriverRule;

use App\Models\DriverRule;
use Livewire\Component;

class Edit extends Component
{
    public DriverRule $driverRule;

    public function mount(DriverRule $driverRule)
    {
        $this->driverRule = $driverRule;
    }

    public function render()
    {
        return view('livewire.driver-rule.edit');
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
