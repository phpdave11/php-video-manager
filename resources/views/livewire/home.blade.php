<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Latest Videos</h1>

    @if ($videos->isEmpty())
        <p class="text-gray-600">No videos uploaded yet.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($videos as $video)
                <a href="{{ route('videos.show', $video->id) }}" class="block group bg-white">
                    <div class="rounded overflow-hidden shadow hover:shadow-lg transition">
                        @if ($video->thumbnail_path)
                            <img src="{{ Storage::disk('s3')->temporaryUrl($video->thumbnail_path, now()->addMinutes(10)) }}"
                                 alt="{{ $video->title }}"
                                 class="w-full h-48 object-cover group-hover:opacity-90 transition">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                                No thumbnail
                            </div>
                        @endif
                        <div class="p-3">
                            <h2 class="font-semibold text-lg truncate">
                                {{ $video->title }}
                            </h2>
                            <p class="text-xs text-gray-500">
                                Uploaded {{ $video->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
