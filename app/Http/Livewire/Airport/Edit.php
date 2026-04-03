<?php

namespace App\Http\Livewire\Airport;

use App\Models\Airport;
use Livewire\Component;

class Edit extends Component
{
    public Airport $airport;

    public function mount(Airport $airport)
    {
        $this->airport = $airport;
    }

    public function render()
    {
        return view('livewire.airport.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->airport->save();

        return redirect()->route('admin.airports.index');
    }

    protected function rules(): array
    {
        return [
            'airport.airport_lat' => [
                'string',
                'nullable',
            ],
            'airport.airport_lng' => [
                'string',
                'nullable',
            ],
            'airport.airport_name' => [
                'string',
                'nullable',
            ],
            'airport.city_location' => [
                'string',
                'nullable',
            ],
            'airport.country' => [
                'string',
                'nullable',
            ],
            'airport.state' => [
                'string',
                'nullable',
            ],
        ];
    }
}
