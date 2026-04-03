<?php

namespace App\Http\Livewire\DriverDocument;

use App\Models\DriverDocument;
use Livewire\Component;

class Create extends Component
{
    public DriverDocument $driverDocument;

    public function mount(DriverDocument $driverDocument)
    {
        $this->driverDocument = $driverDocument;
    }

    public function render()
    {
        return view('livewire.driver-document.create');
    }

    public function submit()
    {
        $this->validate();

        $this->driverDocument->save();

        return redirect()->route('admin.driver-documents.index');
    }

    protected function rules(): array
    {
        return [
            'driverDocument.documents' => [
                'string',
                'nullable',
            ],
        ];
    }
}
