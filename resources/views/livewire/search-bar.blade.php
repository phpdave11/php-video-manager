<div class="flex items-center">
    <input
        type="text"
        wire:model="query"
        wire:keydown.enter="search"
        placeholder="Search videos..."
        class="w-64 px-3 py-2 border rounded-l"
    >
    <button wire:click="search"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r">
        Search
    </button>
</div>
