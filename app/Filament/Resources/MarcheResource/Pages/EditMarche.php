<?php

namespace App\Filament\Resources\MarcheResource\Pages;

use App\Filament\Resources\MarcheResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarche extends EditRecord
{
    protected static string $resource = MarcheResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
