<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\BadgeColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-users'; // Icône pour le menu
    protected static ?string $navigationGroup = 'Gestion des accès'; // Regroupement dans le menu Filament

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required()
                    ->hiddenOn('edit') // Cache le champ mot de passe lors de l'édition

                // Sélection des rôles avec Spatie
                ,
                Select::make('roles')
                    ->label('Rôle')
                    ->options(Role::pluck('name', 'name'))
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->relationship('roles', 'name'), // Spatie définit la relation entre User et Role
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Rôles')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)

            ])
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
