<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        {{-- Drag & Drop Area --}}
        <div
            x-data="{ isDropping: false, isUploading: false, progress: 0 }"
            x-on:livewire-upload-start="isUploading = true; progress = 0"
            x-on:livewire-upload-finish="isUploading = false; progress = 0"
            x-on:livewire-upload-error="isUploading = false; progress = 0"
            x-on:livewire-upload-progress="progress = $event.detail.progress"
            class="relative border-2 border-dashed rounded p-8 text-center transition-colors duration-200"
            :class="isDropping ? 'bg-blue-100 border-blue-400' : 'bg-gray-50 border-gray-300'"
            x-on:dragover.prevent="isDropping = true"
            x-on:dragleave.prevent="isDropping = false"
            x-on:drop.prevent="
                isDropping = false;
                $refs.fileInput.files = $event.dataTransfer.files;
                $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            "
        >
            {{-- File input --}}
            <input type="file"
                   x-ref="fileInput"
                   wire:model="videoFile"
                   accept="video/*"
                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            >

            {{-- Message --}}
            <div class="pointer-events-none">
                @if ($videoFile)
                    <p class="text-green-600 font-medium">
                        File selected: {{ $videoFile->getClientOriginalName() }}
                    </p>
                @else
                    <p class="text-gray-600">Drag & drop a video here, or click to select</p>
                @endif
            </div>

            {{-- Progress bar --}}
            <div x-show="isUploading" class="absolute bottom-0 left-0 right-0 h-2 bg-gray-200 rounded-b">
                <div class="h-full bg-blue-600 rounded-b"
                     :style="`width: ${progress}%; transition: width 0.2s;`"></div>
            </div>
        </div>

        @error('videoFile')
            <span class="text-red-600">{{ $message }}</span>
        @enderror

        {{-- Title --}}
        <div>
            <label class="block font-medium">Title</label>
            <input type="text" wire:model="title" class="w-full border rounded px-3 py-2" />
            @error('title') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-medium">Description</label>
            <textarea wire:model="description" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        {{-- Tags --}}
        <div>
            <label class="block font-medium">Tags (use #hashtags or just words)</label>
            <input type="text" wire:model="tags" class="w-full border rounded px-3 py-2" placeholder="#funny #cringe" />
        </div>

        {{-- Submit --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Upload
        </button>
    </form>
</div>
