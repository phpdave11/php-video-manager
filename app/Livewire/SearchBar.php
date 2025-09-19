<?php

namespace App\Livewire;

use Livewire\Component;

class SearchBar extends Component
{
    public $query = '';

    public function mount()
    {
        $this->query = request()->query('q', '');
    }

    public function search()
    {
        return redirect()->route('videos', ['q' => $this->query]);
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}
