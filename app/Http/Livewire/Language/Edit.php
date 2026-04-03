<?php

namespace App\Http\Livewire\Language;

use App\Models\Language;
use Livewire\Component;

class Edit extends Component
{
    public Language $language;

    public function mount(Language $language)
    {
        $this->language = $language;
    }

    public function render()
    {
        return view('livewire.language.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->language->save();

        return redirect()->route('admin.languages.index');
    }

    protected function rules(): array
    {
        return [
            'language.enable' => [
                'boolean',
            ],
            'language.code' => [
                'string',
                'nullable',
            ],
            'language.is_deleted' => [
                'boolean',
            ],
            'language.is_rtl' => [
                'boolean',
            ],
            'language.name' => [
                'string',
                'nullable',
            ],
        ];
    }
}
