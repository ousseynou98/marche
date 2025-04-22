<x-filament::page>
    <div class="space-y-4">
        @forelse ($this->getNotifications() as $notification)
            <div class="p-4 bg-white shadow rounded">
                <div class="flex justify-between">
                    <div>
                        <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                        <p class="text-sm text-gray-600">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    <div>
                        @if (!empty($notification->data['url']))
                            <a href="{{ $notification->data['url'] }}" class="text-primary-600 underline">Voir</a>
                        @endif
                    </div>
                </div>
                @if (is_null($notification->read_at))
                    <form method="POST" wire:submit.prevent="markAsRead('{{ $notification->id }}')">
                        @csrf
                        <x-filament::button type="submit" size="sm" class="mt-2">Marquer comme lue</x-filament::button>
                    </form>
                @else
                    <p class="text-xs text-gray-400 mt-2">Lue</p>
                @endif
            </div>
        @empty
            <p>Aucune notification pour le moment.</p>
        @endforelse
    </div>
</x-filament::page>
