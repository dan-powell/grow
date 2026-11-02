<?php

declare(strict_types=1);

namespace App\Filament\Resources\SubjectResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class AlertsRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'alerts';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('enabled')
                    ->required(),
                TextInput::make('value')
                    ->numeric()
                    ->required(),
                Toggle::make('lower')
                    ->label('Alert when lower than value')
                    ->required(),
                Toggle::make('email')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ToggleColumn::make('enabled'),
                TextColumn::make('value'),
                IconColumn::make('lower')
                    ->boolean(),
                IconColumn::make('email')
                    ->boolean(),
                TextColumn::make('activated')
                    ->dateTime()
                    ->placeholder('Not active'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
