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
                    ->helperText('Turn off to stop checking this alert without deleting it.')
                    ->required(),
                TextInput::make('value')
                    ->helperText('The threshold, compared with the calibrated reading.')
                    ->numeric()
                    ->required(),
                Toggle::make('lower')
                    ->label('Alert when lower than value')
                    ->helperText('On: alert when the reading drops below the value, e.g. dry soil. Off: alert when it rises above it.')
                    ->required(),
                Toggle::make('email')
                    ->helperText('Email users with "Receive alerts" on when the alert is raised and when it resolves. Alerts are always logged.')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('Checked against each reading from the figure this subject was assigned to when the reading was taken. An alert is raised once when a reading crosses the value, and resolves when a later reading comes back across it.')
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
