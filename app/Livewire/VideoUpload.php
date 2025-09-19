<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Services\VideoService;
use App\Models\Video;
use Illuminate\Http\File;

class VideoUpload extends Component
{
    use WithFileUploads;

    public $videoFile;
    public $title;
    public $description;
    public $tags = '';

    protected $rules = [
        'videoFile' => 'required|file|max:1048576',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'tags' => 'nullable|string',
    ];

    protected function parseTags(string $input): array
    {
        // Split on whitespace
        $parts = preg_split('/\s+/', trim($input));

        $tags = [];
        foreach ($parts as $part) {
            $tag = ltrim($part, '#');
            $tag = trim($tag);
            if (! empty($tag)) {
                $tags[] = strtolower($tag);
            }
        }

        return array_unique($tags);
    }

    // Suggest a title based on the video's filename
    public function updatedVideoFile()
    {
        if ($this->videoFile) {
            // Get original filename
            $originalName = $this->videoFile->getClientOriginalName();
            $title = pathinfo($originalName, PATHINFO_FILENAME);
            if (empty($this->title)) {
                $this->title = $title;
            }
        }
    }

    public function save(VideoService $videoService)
    {
        $this->validate();

        $disk = config('filesystems.default', 'local');

        // Save locally first
        $localPath = $this->videoFile->store('uploads', $disk);
        $absoluteLocalPath = Storage::disk($disk)->path($localPath);

        // Parse hashtags
        $tagsArray = $this->parseTags($this->tags);

        // Use the service
        $video = $videoService->processAndStore(
            $absoluteLocalPath,
            $this->title,
            $this->description,
            $tagsArray,
            auth()->id()
        );

        // Remove local video
        Storage::disk($disk)->delete($localPath);

        return redirect()->route('videos.show', $video->id);
    }

    public function render()
    {
        return view('livewire.video-upload');
    }
}
