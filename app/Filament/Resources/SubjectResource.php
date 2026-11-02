<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enum\Icons;
use App\Filament\Resources\SubjectResource\Pages\CreateSubject;
use App\Filament\Resources\SubjectResource\Pages\EditSubject;
use App\Filament\Resources\SubjectResource\Pages\ListSubjects;
use App\Filament\Resources\SubjectResource\RelationManagers\AlertsRelationManager;
use App\Filament\Resources\SubjectResource\RelationManagers\AssignmentsRelationManager;
use App\Models\Subject;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubjectResource extends Resource
{
    #[\Override]
    protected static ?string $model = Subject::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring';

    #[\Override]
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('The thing a figure measures, such as a plant or a greenhouse. When you rotate plants on a sensor, assign the new subject to the figure rather than renaming it, so each plant keeps its own history.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->helperText('Shown on the site in place of the figure\'s name while the subject is assigned.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('summary')
                            ->helperText('Shown on the figure card. If empty, the figure\'s summary is used.')
                            ->maxLength(255),
                        Select::make('icon')
                            ->helperText('Shown on the figure card while assigned, with the figure\'s icon as a small badge.')
                            ->options(Icons::selectOptions())
                            ->allowHtml()
                            ->searchable(),
                        FileUpload::make('icon_custom')
                            ->helperText('Upload an image to use instead of the icon above.')
                            ->disk('public'),
                        FileUpload::make('image')
                            ->helperText('Not currently shown on the site.')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('4:3')
                            ->imageResizeTargetWidth('1024')
                            ->imageResizeTargetHeight('768'),
                        Textarea::make('notes')
                            ->helperText('Private notes. Only shown here.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('The things being measured. Alerts belong to subjects, so they follow a plant when it moves to another sensor. Assign subjects from a figure.')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('summary')->searchable(),
                TextColumn::make('currentAssignment.figure.name')
                    ->label('Figure')
                    ->placeholder('Unassigned')
                    ->url(fn (Subject $record): ?string => $record->currentAssignment?->figure ? FiguresResource::getUrl('edit', ['record' => $record->currentAssignment->figure]) : null),
                TextColumn::make('currentAssignment.figure.device.name')
                    ->label('Device')
                    ->url(fn (Subject $record): ?string => $record->currentAssignment?->figure?->device ? DeviceResource::getUrl('edit', ['record' => $record->currentAssignment->figure->device]) : null),
                TextColumn::make('alerts_count')
                    ->label('Active alerts')
                    ->counts(['alerts' => fn (Builder $query) => $query->whereNotNull('activated')]),
            ])
            ->defaultSort('name')
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
            AlertsRelationManager::class,
            AssignmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubjects::route('/'),
            'create' => CreateSubject::route('/create'),
            'edit' => EditSubject::route('/{record}/edit'),
        ];
    }
}
