<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\{Pages, RelationManagers};
use App\Models\User;
use Filament\Resources\{Form, Resource, Table};
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\{Builder, SoftDeletingScope};
use Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')->label('Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state)),
                    ]),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Toggle::make('receive_alerts')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('receive_alerts')
                    ->boolean(),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
