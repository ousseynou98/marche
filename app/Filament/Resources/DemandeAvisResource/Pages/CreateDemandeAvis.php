<?php

namespace App\Filament\Resources\DemandeAvisResource\Pages;

use App\Filament\Resources\DemandeAvisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Notifications\DemandeAvisCreeeNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\DemandeAvis;
use App\Models\Marche;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CreateDemandeAvis extends CreateRecord
{
    protected static string $resource = DemandeAvisResource::class;

    
    protected function afterCreate(): void
    {
        $demande = $this->record->load('marche');

        // Log facultatif pour debug
        Log::info('✅ Demande créée avec ID : ' . $demande->id);
        Log::info('✅ Marché lié : ' . ($demande->marche->reference ?? 'aucun'));

        $cpms = User::role('CPM')->get();

        Log::info('✅ Nombre de CPM trouvés : ' . $cpms->count());

        foreach ($cpms as $user) {
            Log::info('📨 Notification en cours d’envoi à : ' . $user->email);

            DB::table('notifications')->insert([
                'id' => Str::uuid(),
                'type' => \App\Notifications\DemandeAvisCreeeNotification::class,
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => json_encode([
                    'title' => 'Nouvelle demande d\'avis',
                    'message' => 'Une nouvelle demande d\'avis a été soumise pour le marché : ' . ($demande->marche->reference ?? 'non défini'),
                    'url' => \App\Filament\Resources\DemandeAvisResource::getUrl('edit', ['record' => $demande]),
                    'icon' => 'heroicon-o-document-text',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            Log::info('✅ Notification enregistrée pour : ' . $user->email);

            // Envoi du mail séparément
            try {
                $user->notify(new DemandeAvisCreeeNotification($demande));

                Log::info('📧 Mail envoyé à : ' . $user->email);
            } catch (\Exception $e) {
                Log::error('❌ Erreur envoi mail à ' . $user->email . ' : ' . $e->getMessage());
            }
        }
    }
}
