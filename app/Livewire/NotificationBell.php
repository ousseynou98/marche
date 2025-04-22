<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public function mount()
    {
        $this->unreadCount = Auth::user()?->unreadNotifications()->count() ?? 0;
    }

    public function markAllAsRead()
    {
        Auth::user()?->unreadNotifications->markAsRead();
        $this->unreadCount = 0;
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
