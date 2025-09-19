<div class="mt-6">
    <h2 class="text-xl font-semibold mb-4">Comments</h2>

    @auth
        <form wire:submit.prevent="post">
            <textarea wire:model.defer="body" class="w-full border rounded p-2" rows="3"
                placeholder="Write a comment..."></textarea>
            @error('body')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
            <button type="submit"
                class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                Post
            </button>
        </form>
    @else
        <p class="text-gray-600">Please log in to comment.</p>
    @endauth

    <div class="mt-6 space-y-4">
        @foreach ($comments as $comment)
            @include('livewire.partials.comment', ['comment' => $comment])
        @endforeach
    </div>
</div>
