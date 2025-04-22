<x-filament::page>
    <form wire:submit.prevent="import" class="space-y-4">
        <x-filament::card>
            <x-filament::input.file-upload wire:model="file" label="Fichier Excel" required />
            <x-filament::button type="submit" color="primary">Importer</x-filament::button>
        </x-filament::card>
    </form>

    @if (session()->has('success'))
        <div class="text-green-500 font-bold">
            {{ session('success') }}
        </div>
    @endif
</x-filament::page>
