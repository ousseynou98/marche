<?php

// namespace App\Filament\Resources\DemandeAvisResource\Pages;

// use App\Filament\Resources\DemandeAvisResource;
// use Filament\Resources\Pages\Page;

// class TraiterDemandeAvis extends Page
// {
//     protected static string $resource = DemandeAvisResource::class;

//     protected static string $view = 'filament.resources.demande-avis-resource.pages.traiter-demande-avis';
// }

namespace App\Filament\Resources\DemandeAvisResource\Pages;

use App\Filament\Resources\DemandeAvisResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;

class TraiterDemandeAvis extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = DemandeAvisResource::class;

    protected static string $view = 'filament.resources.demande-avis-resource.pages.traiter-demande-avis';

    public static function getRouteName(?string $panel = null): string
    {
        // return 'filament.admin.resources.demande-avis-resource.pages.traiter-demande-avis';
        return static::generateRouteName($panel, 'traiter-demande-avis');
    }

    public $record;

    public function mount($record): void
    {
        $this->record = \App\Models\DemandeAvis::findOrFail($record);

        abort_if($this->record->assigned_to !== auth()->id(), 403); // Sécurité
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->record)
            ->schema([
                Textarea::make('projet_avis')->label('Projet d\'avisv')->required(),
                Textarea::make('avis_final')->label('Avis validé par CPM')->nullable(),
            ]);
    }

    public function submit(): void
    {
        $this->record->update($this->form->getState());

        // Optionnel : modifier le statut
        $this->record->update(['statut' => 'Traité']);

        session()->flash('success', 'Demande traitée avec succès.');

        $this->redirect(DemandeAvisResource::getUrl('index'));
    }
}

