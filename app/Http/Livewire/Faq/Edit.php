<?php

namespace App\Http\Livewire\Faq;

use App\Models\Faq;
use Livewire\Component;

class Edit extends Component
{
    public Faq $faq;

    public function mount(Faq $faq)
    {
        $this->faq = $faq;
    }

    public function render()
    {
        return view('livewire.faq.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->faq->save();

        return redirect()->route('admin.faqs.index');
    }

    protected function rules(): array
    {
        return [
            'faq.description' => [
                'string',
                'nullable',
            ],
            'faq.enable' => [
                'boolean',
            ],
            'faq.title' => [
                'string',
                'nullable',
            ],
        ];
    }
}
