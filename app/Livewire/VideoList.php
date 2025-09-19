<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Video;

class VideoList extends Component
{
    public $search = '';

    public function mount()
    {
        $this->search = request()->query('q', '');
    }

    public function render()
    {
        $query = Video::query();

        if ($this->search) {
            $search = strtolower($this->search);
            $tag = str_replace('#', '', $search);
            $query->where('title', 'like', "%$search%")
                  ->orWhere('tags', 'like', "%$tag%");
        }

        $videos = $query->latest()->get();

        return view('livewire.video-list', compact('videos'));
    }
}
