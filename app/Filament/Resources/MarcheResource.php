<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarcheResource\Pages;
use App\Models\Marche;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MarcheImport;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Checkbox;


class MarcheResource extends Resource
{
    protected static ?string $model = Marche::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('reference')
                    ->required()
                    ->unique(),
                TextInput::make('realisation_envisagee')
                    ->required(),
                Select::make('type_marche')
                    ->options([
                        'Services' => 'Services',
                        'Travaux' => 'Travaux',
                        'Fournitures' => 'Fournitures',
                    ])
                    ->required(),
                Select::make('source_financement')
                    ->options([
                        'Fonds ANAM' => 'Fonds ANAM',
                        'Budget National' => 'Budget National',
                        'Bailleur de Fonds' => 'Bailleur de Fonds',
                        'Autre' => 'Autre',
                    ])
                    ->required(),
                    Select::make('mode_passation')
                    ->options([
                        'Appel d\'offres ouvert' => 'Appel d\'offres ouvert',
                        'Appel d\'offres restreint' => 'Appel d\'offres restreint',
                        'Demande de renseignements et de prix à compétition ouverte' => 'Demande de renseignements et de prix à compétition ouverte',
                        'Demande de renseignements et de prix à compétition restreinte' => 'Demande de renseignements et de prix à compétition restreinte',
                        'Entente directe' => 'Entente directe',
                        'Appel à manifestation d\'intérêt' => 'Appel à manifestation d\'intérêt',
                        'Concours' => 'Concours',
                        'Marché de partenariat public-privé' => 'Marché de partenariat public-privé',
                    ])
                    ->required()
                    ->searchable(),
                DatePicker::make('date_lancement'),
                DatePicker::make('date_attribution'),
                DatePicker::make('date_demarrage'),
                DatePicker::make('date_achevement'),
                TextInput::make('montant')
                    ->numeric()
                    ->required(),
                Textarea::make('publicite'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')->sortable()->searchable(),
                TextColumn::make('realisation_envisagee')->limit(30),
                TextColumn::make('type_marche')->sortable(),
                TextColumn::make('source_financement'),
                TextColumn::make('mode_passation'),
                TextColumn::make('date_lancement')->date(),
                TextColumn::make('date_attribution')->date(),
                TextColumn::make('date_demarrage')->date(),
                TextColumn::make('date_achevement')->date(),
                TextColumn::make('montant')->money('XOF'),
                TextColumn::make('publicite')->limit(20),
            ])
            ->filters([])
            ->actions([
                Action::make('import')
                    ->label('Importer Marchés')
                    ->form([
                        FileUpload::make('file')
                            ->required()
                            ->label('Sélectionner un fichier')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv']),
                    ])
                    ->action(function (array $data) {
                        Excel::import(new MarcheImport, $data['file']);
                        Filament\Notifications\Notification::make()
                            ->title('Importation réussie !')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                BulkAction::make('import')
                    ->label('Importer Marchés')
                    ->action(fn ($records) => Excel::import(new MarcheImport, request()->file('file')))
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->required()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv']),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarches::route('/'),
            'create' => Pages\CreateMarche::route('/create'),
            'edit' => Pages\EditMarche::route('/{record}/edit'),
        ];
    }
}
