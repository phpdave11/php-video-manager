<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class Home extends Component
{
    public function render()
    {
        $videos = Video::latest()->get();

        return view('livewire.home', compact('videos'));
    }
}
