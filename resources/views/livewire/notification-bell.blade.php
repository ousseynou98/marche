<div class="relative">
    <button wire:click="markAllAsRead" class="relative focus:outline-none">
        🔔
        @if ($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1">
                {{ $unreadCount }}
            </span>
        @endif
    </button>
</div>
