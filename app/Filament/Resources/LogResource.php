<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages\ManageLogs;
use App\Models\Log;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogResource extends Resource
{
    #[\Override]
    protected static ?string $model = Log::class;

    #[\Override]
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('severity')
                    ->maxLength(255),
                TextInput::make('icon')
                    ->maxLength(255),
                TextInput::make('summary')
                    ->maxLength(255),
                TextInput::make('device.name')
                    ->maxLength(26),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('severity'),
                TextColumn::make('icon'),
                TextColumn::make('created_at')->sortable()
                    ->dateTime(),
                TextColumn::make('device.name'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLogs::route('/'),
        ];
    }
}
