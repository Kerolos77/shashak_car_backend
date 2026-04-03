<?php

namespace App\Http\Livewire\Referral;

use App\Models\Referral;
use Livewire\Component;

class Create extends Component
{
    public Referral $referral;

    public function mount(Referral $referral)
    {
        $this->referral = $referral;
    }

    public function render()
    {
        return view('livewire.referral.create');
    }

    public function submit()
    {
        $this->validate();

        $this->referral->save();

        return redirect()->route('admin.referrals.index');
    }

    protected function rules(): array
    {
        return [
            'referral.referral_by' => [
                'string',
                'nullable',
            ],
            'referral.referral_code' => [
                'string',
                'nullable',
            ],
        ];
    }
}
