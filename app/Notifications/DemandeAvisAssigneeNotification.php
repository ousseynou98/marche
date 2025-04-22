<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DemandeAvis;

class DemandeAvisAssigneeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DemandeAvis $demande;

    /**
     * Créer une nouvelle notification.
     */
    public function __construct(DemandeAvis $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Définir les canaux.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // pour affichage dans Filament
    }

    /**
     * Contenu stocké dans la base pour affichage.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Demande d\'avis assignée',
            'message' => 'Vous avez été assigné à une demande d\'avis pour le marché : ' . $this->demande->marche->reference,
            'url' => route('filament.admin.resources.demande-avis-resource.edit', $this->demande->id),
            'icon' => 'heroicon-o-user',
        ];
    }

    /**
     * Facultatif — utile si tu veux aussi utiliser toArray().
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
