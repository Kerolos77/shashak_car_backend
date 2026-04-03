<?php

namespace App\Http\Livewire\Document;

use App\Models\Document;
use Livewire\Component;

class Create extends Component
{
    public Document $document;

    public function mount(Document $document)
    {
        $this->document             = $document;
        $this->document->back_side  = false;
        $this->document->enable     = false;
        $this->document->expire_at  = false;
        $this->document->front_side = false;
        $this->document->is_deleted = false;
    }

    public function render()
    {
        return view('livewire.document.create');
    }

    public function submit()
    {
        $this->validate();

        $this->document->save();

        return redirect()->route('admin.documents.index');
    }

    protected function rules(): array
    {
        return [
            'document.back_side' => [
                'boolean',
            ],
            'document.enable' => [
                'boolean',
            ],
            'document.expire_at' => [
                'boolean',
            ],
            'document.front_side' => [
                'boolean',
            ],
            'document.title' => [
                'string',
                'nullable',
            ],
            'document.is_deleted' => [
                'boolean',
            ],
        ];
    }
}
