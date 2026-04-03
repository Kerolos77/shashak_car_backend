<?php

namespace App\Http\Livewire\Coupon;

use App\Models\Coupon;
use Livewire\Component;

class Edit extends Component
{
    public Coupon $coupon;

    public function mount(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

    public function render()
    {
        return view('livewire.coupon.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->coupon->save();

        return redirect()->route('admin.coupons.index');
    }

    protected function rules(): array
    {
        return [
            'coupon.amount' => [
                'string',
                'required',
            ],
            'coupon.code' => [
                'string',
                'required',
            ],
            'coupon.is_public' => [
                'string',
                'nullable',
            ],
            'coupon.title' => [
                'string',
                'nullable',
            ],
            'coupon.type' => [
                'string',
                'nullable',
            ],
        ];
    }
}
