<?php

namespace App\Http\Livewire\Currency;

use App\Models\Currency;
use Livewire\Component;

class Create extends Component
{
    public Currency $currency;

    public function mount(Currency $currency)
    {
        $this->currency                  = $currency;
        $this->currency->enable          = false;
        $this->currency->symbol_at_right = false;
    }

    public function render()
    {
        return view('livewire.currency.create');
    }

    public function submit()
    {
        $this->validate();

        $this->currency->save();

        return redirect()->route('admin.currencies.index');
    }

    protected function rules(): array
    {
        return [
            'currency.code' => [
                'string',
                'nullable',
            ],
            'currency.decimal_digits' => [
                'string',
                'required',
            ],
            'currency.enable' => [
                'boolean',
            ],
            'currency.name' => [
                'string',
                'required',
            ],
            'currency.symbol' => [
                'string',
                'required',
            ],
            'currency.symbol_at_right' => [
                'boolean',
            ],
        ];
    }
}
