<?php

namespace App\Http\Livewire\Tax;

use App\Models\Tax;
use Livewire\Component;

class Edit extends Component
{
    public Tax $tax;

    public function mount(Tax $tax)
    {
        $this->tax = $tax;
    }

    public function render()
    {
        return view('livewire.tax.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->tax->save();

        return redirect()->route('admin.taxes.index');
    }

    protected function rules(): array
    {
        return [
            'tax.country' => [
                'string',
                'nullable',
            ],
            'tax.enable' => [
                'boolean',
            ],
            'tax.tax' => [
                'string',
                'nullable',
            ],
            'tax.title' => [
                'string',
                'nullable',
            ],
            'tax.type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
