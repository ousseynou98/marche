<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Notifications extends Page
{
    protected static string $view = 'filament.pages.notifications';
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $title = 'Mes notifications';
    protected static ?string $slug = 'notifications';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?int $navigationSort = 100;

    public function getNotifications()
    {
        return Auth::user()->notifications()->latest()->get();
    }

    public function markAsRead($id)
    {
        $notif = Auth::user()->notifications()->find($id);
        if ($notif) {
            $notif->markAsRead();
        }
    }

    public static function getNavigationBadge(): ?string
    {
        return Auth::user()?->unreadNotifications()->count() ?: null;
    }
}
