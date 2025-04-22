<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-4">
        {{ $this->form }}
        <x-filament::button type="submit">
            Soumettre le traitement
        </x-filament::button>
    </form>
</x-filament::page>
