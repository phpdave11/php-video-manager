<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">{{ $video->title }}</h1>

    {{-- Video player --}}
    <div class="flex justify-center">
        <video
            id="videoPlayer"
            controls
            autoplay
            class="max-h-[70vh] w-auto rounded shadow"
        >
            <source src="{{ $videoUrl }}" type="{{ $video->metadata['mime_type'] ?? 'video/mp4' }}">
            Your browser does not support the video tag.
        </video>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('videoPlayer');

            // Try autoplay with sound
            video.play().catch(() => {
                // If blocked, mute and try again
                video.muted = true;
                video.play();
            });
        });
    </script>

    {{-- Description --}}
    <p class="mb-4 text-gray-700">{{ $video->description }}</p>

    {{-- Tags --}}
    @if (!empty($video->tags))
        <div class="mb-4">
            @foreach ($video->tags as $tag)
                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm mr-2">
                    <a href="{{ route('videos', ['q' => '#' . $tag]) }}">#{{ $tag }}</a>
                </span>
            @endforeach
        </div>
    @endif

    {{-- Metadata --}}
    <div class="text-sm text-gray-600">
        <p><strong>Length:</strong>
            @if (isset($video->metadata['duration']))
                {{ gmdate("H:i:s", (int) $video->metadata['duration']) }}
            @else
                Unknown
            @endif
        </p>
        <p><strong>Uploaded:</strong> {{ $video->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <livewire:comments :video="$video" />
</div>
