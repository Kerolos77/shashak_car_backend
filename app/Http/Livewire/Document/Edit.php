<?php

namespace App\Http\Livewire\Document;

use App\Models\Document;
use Livewire\Component;

class Edit extends Component
{
    public Document $document;

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    public function render()
    {
        return view('livewire.document.edit');
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
