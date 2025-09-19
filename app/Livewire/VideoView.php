<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoView extends Component
{
    public Video $video;
    public string $videoUrl;

    public function mount(Video $video)
    {
        $this->video = $video;

        // Generate a presigned URL (valid for 1 hour)
        $this->videoUrl = Storage::disk('s3')
            ->temporaryUrl($video->path, now()->addHour());
    }

    public function render()
    {
        return view('livewire.video-view');
    }
}
