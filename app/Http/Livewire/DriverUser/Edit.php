<?php

namespace App\Http\Livewire\DriverUser;

use App\Models\DriverUser;
use Livewire\Component;

class Edit extends Component
{
    public DriverUser $driverUser;

    public function mount(DriverUser $driverUser)
    {
        $this->driverUser = $driverUser;
    }

    public function render()
    {
        return view('livewire.driver-user.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->driverUser->save();

        return redirect()->route('admin.driver-users.index');
    }

    protected function rules(): array
    {
        return [
            'driverUser.country_code' => [
                'string',
                'nullable',
            ],
            'driverUser.document_verification' => [
                'boolean',
            ],
            'driverUser.email' => [
                'email:rfc',
                'nullable',
            ],
            'driverUser.fcm_token' => [
                'string',
                'nullable',
            ],
            'driverUser.full_name' => [
                'string',
                'nullable',
            ],
            'driverUser.is_online' => [
                'boolean',
            ],
            'driverUser.login_type' => [
                'string',
                'nullable',
            ],
            'driverUser.phone_number' => [
                'integer',
                'min:-2147483648',
                'max:2147483647',
                'nullable',
            ],
            'driverUser.profile_pic' => [
                'string',
                'nullable',
            ],
            'driverUser.reviews_count' => [
                'string',
                'nullable',
            ],
            'driverUser.reviews_sum' => [
                'string',
                'nullable',
            ],
            'driverUser.rotation' => [
                'string',
                'nullable',
            ],
            'driverUser.service' => [
                'string',
                'nullable',
            ],
            'driverUser.wallet_amount' => [
                'string',
                'nullable',
            ],
        ];
    }
}
