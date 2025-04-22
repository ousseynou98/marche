<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\DemandeAvis;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;    
use App\Models\User;

class DemandeAvisCreeeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public DemandeAvis $demande;

    public function __construct(DemandeAvis $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Channels utilisés.
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // ⬅️ Utilisation du canal database pour Filament
    }

    /**
     * Contenu de la notification pour la base de données
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle demande d\'avis')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Une nouvelle demande d\'avis a été soumise.')
            ->line('Marché concerné : ' . $this->demande->marche->reference)
            ->action(
                'Consulter',
                url('/admin/demande-avis/' . $this->demande->id . '/edit')
            )
            ->line('Merci de votre collaboration.');
    }


    public function toDatabase(object $notifiable): array
    {
        // Log::info('📨 Notification en cours d’envoi à : ' . $notifiable->email);
        Log::info('Appel à toDatabase() pour user ID ' . $notifiable->id);
        return [
            'title' => 'Nouvelle demande d\'avis',
            'message' => 'Une nouvelle demande d\'avis a été soumise pour le marché : ' . $this->demande->marche->reference,
            'url' => route('filament.admin.resources.demande-avis-resource.edit', $this->demande->id),
            'icon' => 'heroicon-o-document-text',
        ];
    }
    /**
     * Optionnel : Représentation en tableau
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
