<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\Icons;
use App\Filament\Actions\AssignSubjectAction;
use App\Filament\Resources\FiguresResource\Pages\CreateFigures;
use App\Filament\Resources\FiguresResource\Pages\EditFigures;
use App\Filament\Resources\FiguresResource\Pages\ListFigures;
use App\Filament\Resources\FiguresResource\RelationManagers\AssignmentsRelationManager;
use App\Models\Figure;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class FiguresResource extends Resource
{
    #[\Override]
    protected static ?string $model = Figure::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('device_id')
                    ->relationship('device', 'name')
                    ->helperText('The device that reports this value.')
                    ->required(),
                ...self::figureFields(),
            ]);
    }

    /**
     * Figure form fields, shared with the device's figures relation manager.
     */
    public static function figureFields(): array
    {
        return [
            Section::make()
                ->description('A figure is one value a device reports, such as a temperature or a soil moisture probe. Name it after the sensor; what it measures is set by assigning a subject.')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->helperText('The sensor\'s own name, e.g. "Moisture probe 2". While a subject is assigned, the site shows the subject\'s name instead.')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('key')
                        ->helperText('The key this value has in the readings the device sends, e.g. "moisture_1". Readings with a key that doesn\'t match a figure are ignored.')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('summary')
                        ->helperText('Shown on the figure card when the current subject has no summary of its own.')
                        ->maxLength(255),
                    TextInput::make('chart')
                        ->helperText('Not currently used.')
                        ->maxLength(255),
                    Select::make('icon')
                        ->helperText('Shown on the figure card. Defaults to a sprout.')
                        ->options(array_combine(Icons::names(), Icons::names())),
                    FileUpload::make('icon_custom')
                        ->helperText('Upload an image to use instead of the icon above.'),
                    Select::make('icon_small')
                        ->helperText('A small badge shown on the corner of the icon.')
                        ->options(array_combine(Icons::names(), Icons::names())),
                    TextInput::make('location')
                        ->helperText('Not currently shown on the site.')
                        ->maxLength(255),
                ]),
            Section::make('Data Configuration')
                ->description('How readings are shown. These settings belong to the sensor, so they stay the same when subjects are rotated.')
                ->columns(2)
                ->schema([
                    TextInput::make('prefix')
                        ->helperText('Shown before the value, e.g. "£".')
                        ->maxLength(255),
                    TextInput::make('suffix')
                        ->helperText('Shown after the value, e.g. "℃" or "%".')
                        ->maxLength(255),
                    Section::make('Calibration')
                        ->description('Corrects a sensor that reads consistently high or low. Applies to displayed values, the range bar and alert checks. Raw readings are stored unchanged and calibrated when shown, so changing this also changes past readings.')
                        ->columns(6)
                        ->schema([
                            Toggle::make('calibrate')
                                ->label('Enabled')
                                ->required()
                                ->reactive()
                                ->inline(false)
                                ->columnSpan(1),
                            Toggle::make('calibrate_percentage')
                                ->label('Percentage?')
                                ->inline(false)
                                ->columnSpan(1),
                            TextInput::make('calibrate_value')
                                ->label('Value')
                                ->helperText('Added to the raw reading; use a negative number to reduce it. With Percentage on, 10 adds 10% of the reading instead of 10.')
                                ->required(fn (Get $get) => $get('calibrate'))
                                ->numeric()
                                ->columnSpan(4),
                        ]),
                    Section::make('Range Bar Chart')
                        ->description('The bar on the figure card shows where the latest reading sits between Min and Max, coloured on a blend from the Min colour to the Max colour. Leave Min or Max empty to hide the bar.')
                        ->columns(2)
                        ->schema([
                            TextInput::make('range_min')
                                ->label('Min')
                                ->helperText('Readings at or below this fill none of the bar.')
                                ->lt('range_max')
                                ->numeric(),
                            TextInput::make('range_max')
                                ->label('Max')
                                ->helperText('Readings at or above this fill the whole bar.')
                                ->gt('range_min')
                                ->numeric(),
                            ColorPicker::make('range_min_color')
                                ->rgb()
                                ->label('Min Color')
                                ->helperText('If only one colour is set, it blends with white. If neither is set, a default green is used.'),
                            ColorPicker::make('range_max_color')
                                ->rgb()
                                ->label('Max Color'),
                        ]),
                ]),
            Section::make()
                ->schema([
                    Toggle::make('dashboard')
                        ->helperText('Show this figure on the dashboard. A device\'s page always shows all of its figures.')
                        ->required(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Each figure is one value reported by a device. When you move a sensor to a different plant, use "Assign subject" rather than renaming the figure. Drag rows to change the display order.')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('key')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('currentSubject.name')
                    ->label('Subject')
                    ->placeholder('Unassigned')
                    ->url(fn (Figure $record): ?string => $record->currentSubject ? SubjectResource::getUrl('edit', ['record' => $record->currentSubject]) : null),
                TextColumn::make('summary')->sortable()->searchable(),
                TextColumn::make('device.name')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Figure $record): ?string => $record->device ? DeviceResource::getUrl('edit', ['record' => $record->device]) : null),
                ToggleColumn::make('dashboard'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                AssignSubjectAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make(),
            ])
            ->defaultSort('order')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [
            AssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFigures::route('/'),
            'create' => CreateFigures::route('/create'),
            'edit' => EditFigures::route('/{record}/edit'),
        ];
    }
}
