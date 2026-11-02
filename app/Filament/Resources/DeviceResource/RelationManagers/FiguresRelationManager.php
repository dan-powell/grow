<?php

declare(strict_types=1);

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Filament\Actions\AssignSubjectAction;
use App\Filament\Resources\FiguresResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FiguresRelationManager extends RelationManager
{
    #[\Override]
    protected static string $relationship = 'figures';

    #[\Override]
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(FiguresResource::figureFields());
    }

    public function table(Table $table): Table
    {
        return $table
            ->description('The values this device reports. Each one is matched to readings by its key. Drag rows to change the display order.')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('currentSubject.name')->label('Subject')->placeholder('Unassigned'),
                TextColumn::make('summary')->sortable()->searchable(),
                TextColumn::make('key')->sortable()->searchable(),
                TextColumn::make('last_reading.created_at')->datetime(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->filters([
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                AssignSubjectAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'order';
    }
}
