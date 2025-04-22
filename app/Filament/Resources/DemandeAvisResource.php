<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DemandeAvisResource\Pages;
use App\Filament\Resources\DemandeAvisResource\RelationManagers;
use App\Models\DemandeAvis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;


class DemandeAvisResource extends Resource
{
    protected static ?string $model = DemandeAvis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Select::make('marche_id')
                ->label('Marché Concerné')
                ->options(\App\Models\Marche::pluck('reference', 'id')) // Récupère les marchés existants
                ->searchable()
                ->required(),

            FileUpload::make('document')
                ->label('Document de demande d\'avis')
                ->directory('demandes_avis') // Stocke les fichiers dans storage/app/public/demandes_avis
                ->required(),

            Textarea::make('commentaire')
                ->label('Commentaire')
                ->placeholder('Ajoutez un commentaire si nécessaire')
                ->nullable(),

            // Champ caché pour stocker l'utilisateur actuel (DAGE qui fait la demande)
            Forms\Components\Hidden::make('created_by')
                ->default(Auth::id()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDemandeAvis::route('/'),
            'create' => Pages\CreateDemandeAvis::route('/create'),
            'edit' => Pages\EditDemandeAvis::route('/{record}/edit'),
            'traiter-demande-avis' => Pages\TraiterDemandeAvis::route('/traiter-demande-avis/{record}'),

        ];
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasRole('DAGE');
    } 
}
