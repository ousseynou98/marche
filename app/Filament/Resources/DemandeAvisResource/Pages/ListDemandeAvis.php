<?php

namespace App\Filament\Resources\DemandeAvisResource\Pages;

use App\Filament\Resources\DemandeAvisResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use App\Models\User;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class ListDemandeAvis extends ListRecords
{
    protected static string $resource = DemandeAvisResource::class;

    public function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        // Si Agent CPM : voir uniquement ses demandes assignées
        if (auth()->user()->hasRole('Agent CPM')) {
            $query->where('assigned_to', auth()->id());
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('marche.reference')
                    ->label('Marché')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Date de soumission')
                    ->date('d/m/Y H:i')
                    ->sortable(),

                SelectColumn::make('assigned_to')
                    ->label('Agent en charge')
                    ->options(
                        User::role('Agent CPM')->pluck('name', 'id')
                    )
                    ->default('Non assigné')
                    ->sortable()
                    ->searchable()
                    ->afterStateUpdated(fn ($record, $state) => $record->update(['assigned_to' => $state])),

                // BadgeColumn::make('statut')
                //     ->label('Statut')
                //     ->colors([
                //         'secondary' => 'En attente',
                //         'warning' => 'En attente validation CPM',
                //         'success' => 'Approuvé',
                //         'danger' => 'Rejeté',
                //     ])
                //     ->sortable(),
                SelectColumn::make('statut')
                ->label('Statut')
                ->options([
                    'En attente' => 'En attente',
                    'En attente validation CPM' => 'En attente validation CPM',
                    'Traité' => 'Traité',
                    'Approuvé' => 'Approuvé',
                    'Rejeté' => 'Rejeté',
                ])
                ->sortable()
                ->disabled(fn () => !auth()->user()->hasRole('CPM')) // désactiver pour les non-CPM
                ->afterStateUpdated(function ($record, $state) {
                    $record->update(['statut' => $state]);
                }),

            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Filtrer par statut')
                    ->options([
                        'En attente' => 'En attente',
                        'En attente validation CPM' => 'En attente validation CPM',
                        'Traité' => 'Traité',
                        'Approuvé' => 'Approuvé',
                        'Rejeté' => 'Rejeté',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Action "Voir la demande"
                Action::make('voir_demande')
                    ->label('Voir la demande')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Détail de la demande')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fermer')
                    ->form(fn ($record) => [
                        \Filament\Forms\Components\TextInput::make('marché')
                            ->default($record->marche->reference)
                            ->disabled(),

                            \Filament\Forms\Components\TextInput::make('soumis_par')
                            ->default($record->createdBy->name ?? '-')
                            ->label('Soumis par')
                            ->disabled(),

                        // FileUpload::make('document')
                        //     ->label('Document de demande')
                        //     ->default($record->document)
                        //     ->downloadable()
                        //     ->disabled(),
                        Placeholder::make('document')
                        ->label('Document envoyé par le DAG')
                        ->content(new HtmlString(
                            $record->document
                                ? '<a href="' . asset('storage/' . $record->document) . '" target="_blank" class="text-primary-600 underline">📄 Voir le document</a>'
                                : 'Aucun document'
                        )),

                        // Affichage de l'historique
                        \Filament\Forms\Components\Repeater::make('historique')
                            ->label('Historique des avis')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('avis')
                                    ->label('Avis')
                                    ->default(fn ($state) => $state['avis'])
                                    ->disabled(),

                                    Placeholder::make('projet_avis')
                                    ->label('Projet d\'avis joint par l\'agent')
                                    // ->content(fn ($state) => new HtmlString(
                                    //     !empty($state['projet_avis_document'])
                                    //         ? '<a href="' . asset('storage/' . $state['projet_avis_document']) . '" target="_blank" class="text-primary-600 underline">📄 Voir le projet</a>'
                                    //         : 'Aucun projet joint'
                                    // )),
                                    ->content(fn ($state) => new HtmlString(
                                        isset($state) && is_string($state) && $state !== ''
                                            ? '<a href="' . asset('storage/' . $state) . '" target="_blank" class="text-primary-600 underline">📄 Voir le projet</a>'
                                            : 'Aucun projet joint'
                                    )),
                            

                                \Filament\Forms\Components\TextInput::make('statut')
                                    ->default(fn ($state) => $state['statut'])
                                    ->disabled(),
                            ])
                            // ->default($record->historiques->map(fn ($item) => $item->toArray())->toArray())
                            ->default($record->historiques->map(fn ($item) => [
                                'avis' => $item->avis,
                                'projet_avis' => $item->projet_avis_document,
                                // 'projet_avis_document' => $item->projet_avis_document,
                                'statut' => $item->statut,
                            ])->toArray())                            
                            ->columns(2)
                            ->disabled(),
                            ]),

                // Action "Soumettre un avis"
                Action::make('soumettre_avis')
                    ->label('Soumettre l\'avis')
                    ->icon('heroicon-o-document-text')
                    ->form([
                        Select::make('avis')
                            ->label('Avis de l\'agent')
                            ->options([
                                'favorable' => 'Favorable',
                                'defavorable' => 'Défavorable',
                            ])
                            ->required(),
            
                        FileUpload::make('projet_avis_document')
                            ->label('Projet d\'avis')
                            ->directory('projets_avis')
                            ->required(),
                    ])
                    ->visible(fn ($record) => auth()->user()->hasRole('Agent CPM') && $record->assigned_to === auth()->id())
                    ->action(function ($record, $data) {
                        $record->historiques()->create([
                            'agent_id' => auth()->id(),
                            'avis' => $data['avis'],
                            'projet_avis_document' => $data['projet_avis_document'],
                            'statut' => 'En attente validation CPM',
                        ]);
            
                        $record->update(['statut' => 'En attente validation CPM']);
                    }),
            
                // Action "Valider l'avis"
                Action::make('valider_avis')
                    ->label('Valider l\'avis')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => auth()->user()->hasRole('CPM') && $record->statut === 'En attente validation CPM')
                    ->action(function ($record) {
                        $dernier = $record->historiques()->latest()->first();
            
                        if ($dernier) {
                            $dernier->update(['statut' => 'Validé']);
                        }
            
                        $record->update(['statut' => 'Traité']);
                    }),
            ]);
            
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
