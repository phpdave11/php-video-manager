<div class="mt-2">
    <div class="p-3 border rounded bg-gray-50">
        <p class="text-sm text-gray-700">
            <strong>{{ $comment->user->name }}</strong>
            <span title="{{ $comment->created_at->format('M j, Y g:i A') }}">
                • {{ $comment->created_at->diffForHumans() }}
            </span>
            said:
        </p>
        <p class="mt-1">{{ $comment->body }}</p>

        @auth
            <button wire:click="startReply({{ $comment->id }})"
                    class="text-blue-500 text-xs mt-1">
                Reply
            </button>
        @endauth
    </div>

    {{-- Reply form --}}
    @if ($parentId === $comment->id)
        <form wire:submit.prevent="post({{ $comment->id }})" class="ml-6 mt-2">
            <textarea
                wire:model.defer="replyBody.{{ $comment->id }}"
                class="w-full border rounded p-2"
                rows="2"
                placeholder="Write a reply..."></textarea>
            <div class="mt-1 flex items-center gap-2">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                    Reply
                </button>
                <button type="button"
                        wire:click="cancelReply"
                        class="text-gray-600 hover:text-gray-800 text-sm">
                    Cancel
                </button>
            </div>
            @error("replyBody.$comment->id")
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
    @endif

    {{-- Render replies recursively --}}
    @if ($comment->replies->isNotEmpty())
        <div class="ml-6 mt-2 space-y-2">
            @foreach ($comment->replies as $reply)
                @include('livewire.partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>
