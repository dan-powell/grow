<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hash;

class UserResource extends Resource
{
    #[\Override]
    protected static ?string $model = User::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make()
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->helperText('Leave empty to keep the current password.')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
                Section::make()
                    ->schema([
                        Toggle::make('receive_alerts')
                            ->helperText('Email this user about late readings and subject alerts that have email turned on.')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('People with an account. Only users marked as admin can sign in to this panel; that flag is set in the database, not here.')
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('email_verified_at')
                    ->dateTime(),
                IconColumn::make('receive_alerts')
                    ->boolean(),
            ])
            ->filters([
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
