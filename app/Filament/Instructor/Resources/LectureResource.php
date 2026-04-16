<?php

namespace App\Filament\Instructor\Resources;

use App\Filament\Instructor\Resources\LectureResource\Pages\ListLectures;
use App\Filament\Instructor\Resources\LectureResource\Pages\ViewLecture;
use App\Models\Lecture;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'My Lectures';

    protected static ?string $modelLabel = 'Lecture';

    protected static ?string $pluralModelLabel = 'My Lectures';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $instructor = auth()->user()?->instructor;

        if ($instructor) {
            $query->where('instructor_id', $instructor->id);
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->time('h:i A')
                    ->sortable(),

                TextColumn::make('batch_name')
                    ->label('Batch'),
            ])
            ->filters([
                SelectFilter::make('course')
                    ->relationship('course', 'name'),
            ])
            ->defaultSort('date', 'asc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('date'),

            TimePicker::make('start_time')
                ->seconds(false),

            Select::make('course_id')
                ->relationship('course', 'name'),

            Select::make('instructor_id')
                ->label('Instructor')
                ->relationship('instructor', 'name'),

            TextInput::make('batch_name'),
        ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLectures::route('/'),
            'view' => ViewLecture::route('/{record}'),
        ];
    }
}
