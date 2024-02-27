<x-app-layout>

    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow-lg flex flex-col">
        <div class="p-4">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-2">
                {{ $schematic->name }}
            </h2>
            <div class="flex flex-wrap justify-center">
                @foreach ($schematic->authors as $player)
                    <img src="{{ $player->headUrl }}" alt="{{ $player->lastSeenName }}" class="w-8 h-8 rounded-full m-1">
                @endforeach
            </div>
            <p class="text-gray-500 dark:text-gray-400 mb-4 text-center">
                {{ $schematic->description }}
            </p>
        </div>
        <div class="flex-grow">
            <x-schematic-renderer :schematic="$schematic" />
        </div>
        <div class="flex justify-center p-4">
            {{--  download command div "/download {{ $schematic->id }}" that copies to the clipboard  --}}

            <p class="mb-4 text-center cursor-pointer bg-gray-200 dark:bg-gray-300 dark:text-gray-700 p-2 rounded-lg active:bg-gray-300 hover:bg-gray-300"
                onclick="copyToClipboard('/download {{ $schematic->id }}'); Toast.success('Copied to clipboard')">
                /download {{ $schematic->id }}
            </p>
        </div>
        <div class="flex justify-center p-4">
            <a href="{{ $schematic->downloadLink }}" target="_blank"
                class="text-blue-700 dark:text-white hover:underline">
                Download <i class="fas fa-download text-green-700"></i>
            </a>
            @auth
                @if ($schematic->authors->contains(auth()->user()->player->id))
                    <button wire:click="deleteSchematic('{{ $schematic->id }}')" class="ml-4 text-red-700 dark:text-white">
                        Delete <i class="fas fa-trash text-red-700"></i>

                    </button>
                @endif
            @endauth

        </div>
    </div>
</x-app-layout>
