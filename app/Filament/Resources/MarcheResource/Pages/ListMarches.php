<?php

namespace App\Filament\Resources\MarcheResource\Pages;

use App\Filament\Resources\MarcheResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListMarches extends ListRecords
{
    protected static string $resource = MarcheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('import')
                ->label('Importer Marchés')
                ->icon('heroicon-o-document')
                ->color('primary')
                ->url(route('import.marches')) // Redirige vers la page d'importation
                ->openUrlInNewTab(),
        ];
    }
}
