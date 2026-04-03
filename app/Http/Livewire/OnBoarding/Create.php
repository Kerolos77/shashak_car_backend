<?php

namespace App\Http\Livewire\OnBoarding;

use App\Models\OnBoarding;
use Livewire\Component;

class Create extends Component
{
    public OnBoarding $onBoarding;

    public function mount(OnBoarding $onBoarding)
    {
        $this->onBoarding = $onBoarding;
    }

    public function render()
    {
        return view('livewire.on-boarding.create');
    }

    public function submit()
    {
        $this->validate();

        $this->onBoarding->save();

        return redirect()->route('admin.on-boardings.index');
    }

    protected function rules(): array
    {
        return [
            'onBoarding.description' => [
                'string',
                'nullable',
            ],
            'onBoarding.image' => [
                'string',
                'nullable',
            ],
            'onBoarding.title' => [
                'string',
                'nullable',
            ],
            'onBoarding.type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
