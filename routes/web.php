<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarcheController;
use App\Filament\Pages\ImportMarches;   
use App\Models\User;
use App\Models\DemandeAvis;
use App\Notifications\DemandeAvisCreeeNotification;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/import-marches', [MarcheController::class, 'import'])->name('import.marches');
Route::get('/import-marches', ImportMarches::class)->name('import.marches');

Route::get('/test-mail', function () {
    try {
        Mail::raw('Test bounce', function ($message) {
            $message->to('ousseynoudjite98@gmail.com')
                    ->from('marches@anam-senegal.com')
                    ->replyTo('marches@anam-senegal.com')
                    ->subject('Test bounce');
        });
        return '✅ Mail envoyé avec succès !';

    } catch (\Exception $e) {
        return '❌ Erreur lors de lenvoi : ' . $e->getMessage();
    }
});

