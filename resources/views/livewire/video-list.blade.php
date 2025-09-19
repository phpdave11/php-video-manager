<div>
    <h1 class="text-2xl font-bold mb-4">Videos</h1>

    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="py-2 px-4 border-b">Thumbnail</th>
                <th class="py-2 px-4 border-b">Title</th>
                <th class="py-2 px-4 border-b">Length</th>
                <th class="py-2 px-4 border-b">User</th>
                <th class="py-2 px-4 border-b">Uploaded</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($videos as $video)
                <tr>
                    <td class="py-2 px-4 border-b">
                        @if ($video->thumbnail_path)
                            <a href="{{ route('videos.show', $video->id) }}">
                                <img src="{{ Storage::disk('s3')->temporaryUrl($video->thumbnail_path, now()->addMinutes(10)) }}"
                                     alt="Thumbnail for {{ $video->title }}"
                                     class="h-48 w-auto rounded shadow hover:opacity-80 transition">
                            </a>
                        @else
                            <span class="text-gray-400 italic">No thumbnail</span>
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('videos.show', $video->id) }}" class="text-blue-600 hover:underline">
                            {{ $video->title }}
                        </a>
                    </td>
                    <td class="py-2 px-4 border-b">
                        @if(isset($video->metadata['format']['duration']))
                            {{ gmdate("i:s", (int) $video->metadata['format']['duration']) }}
                        @else
                            <span class="text-gray-400">–</span>
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">{{ $video->user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $video->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
