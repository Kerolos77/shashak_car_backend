<?php

namespace App\Http\Livewire\Service;

use App\Models\Service;
use Livewire\Component;

class Create extends Component
{
    public Service $service;
    public array $listsForFields = [];
    public function mount(Service $service)
    {
        $this->service                 = $service;
        $this->service->enable         = false;
        $this->service->intercity_type = false;
        $this->initListsForFields();
    }
    protected function initListsForFields(): void
    {
        $this->listsForFields['commission_type'] = $this->service::COMISSION_TYPE;
    }
    public function render()
    {
        return view('livewire.service.create');
    }

    public function submit()
    {
        $this->validate();

        $this->service->save();

        return redirect()->route('admin.services.index');
    }

    protected function rules(): array
    {
        return [
            'service.commission_type' => [
                'nullable',
                'in:' . implode(',', array_keys($this->listsForFields['commission_type'])),
            ],
            'service.admin_commission' => [
                'string',
                'nullable',
            ],
            'service.enable' => [
                'boolean',
            ],
            'service.enable' => [
                'boolean',
            ],
            'service.image' => [
                'string',
                'nullable',
            ],
            'service.intercity_type' => [
                'boolean',
            ],
            'service.km_charge' => [
                'string',
                'nullable',
            ],
            'service.offer_rate' => [
                'string',
                'nullable',
            ],
            'service.title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
