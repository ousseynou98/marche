<?php

namespace App\Filament\Resources\DemandeAvisResource\Pages;

use App\Filament\Resources\DemandeAvisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDemandeAvis extends EditRecord
{
    protected static string $resource = DemandeAvisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
