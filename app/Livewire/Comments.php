<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Video;
use App\Models\Comment;

class Comments extends Component
{
    public Video $video;

    // Root comment input
    public string $body = '';

    // Each reply box has its own input: [comment_id => "text"]
    public array $replyBody = [];

    // Which comment is being replied to (controls showing the reply box)
    public ?int $parentId = null;

    public function mount(Video $video)
    {
        $this->video = $video;
    }

    public function startReply(int $commentId): void
    {
        $this->parentId = $commentId;
        $this->replyBody[$commentId] = $this->replyBody[$commentId] ?? '';
    }

    public function cancelReply(): void
    {
        $this->parentId = null;
    }

    public function post(int $parentId = null): void
    {
        if ($parentId) {
            // Validate the reply box for this specific parent
            $this->validate([
                "replyBody.$parentId" => 'required|string|min:1',
            ]);
            $text = $this->replyBody[$parentId];
        } else {
            // Validate the root comment box
            $this->validate([
                'body' => 'required|string|min:1',
            ]);
            $text = $this->body;
        }

        Comment::create([
            'video_id'  => $this->video->id,
            'user_id'   => auth()->id(),
            'parent_id' => $parentId,
            'body'      => $text,
        ]);

        // Reset inputs
        if ($parentId) {
            $this->replyBody[$parentId] = '';
            $this->parentId = null;
        } else {
            $this->body = '';
        }
    }

    public function render()
    {
        // top-level comments + eager-load recursive replies + users
        $comments = Comment::where('video_id', $this->video->id)
            ->whereNull('parent_id')
            ->with('replies', 'user')
            ->latest()
            ->get();

        return view('livewire.comments', compact('comments'));
    }
}
