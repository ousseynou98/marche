<?php

namespace App\Filament\Resources\DemandeAvisResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AvisHistoriqueRelationManager extends RelationManager
{
    protected static string $relationship = 'AvisHistorique';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('php artisan make:filament-relation-manager DemandeAvisResource AvisHistorique')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('php artisan make:filament-relation-manager DemandeAvisResource AvisHistorique')
            ->columns([
                Tables\Columns\TextColumn::make('php artisan make:filament-relation-manager DemandeAvisResource AvisHistorique'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
