<?php

namespace App\Filament\Resources\Lectures;

use App\Filament\Resources\Lectures\Pages\CreateLecture;
use App\Filament\Resources\Lectures\Pages\EditLecture;
use App\Filament\Resources\Lectures\Pages\ListLectures;
use App\Filament\Resources\Lectures\Pages\ViewLecture;
use App\Filament\Resources\Lectures\Schemas\LectureForm;
use App\Filament\Resources\Lectures\Schemas\LectureInfolist;
use App\Filament\Resources\Lectures\Tables\LecturesTable;
use App\Models\Instructor;
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

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    if (auth()->user()->hasRole('instructor')) {

        // safety check (avoid crash if relation missing)
        if (auth()->user()->instructor) {
            $query->where(
                'instructor_id',
                auth()->user()->instructor->id
            );
        } else {
            // no instructor linked → show nothing
            $query->whereRaw('1 = 0');
        }
    }

    return $query;
}

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('date')
                ->required(),

            TimePicker::make('start_time')
                ->seconds(false)
                ->required(),

            Select::make('course_id')
                ->relationship('course', 'name')
                ->required(),

            Select::make('instructor_id')
                ->label('Instructor')
                ->relationship(
                    name: 'instructor',
                    titleAttribute: 'name',
                    modifyQueryUsing: function ($query, callable $get, $livewire) {
                        $date = $get('date');

                        $currentInstructorId = $livewire->record?->instructor_id;
    
                        if ($date) {
                            $query->where(function ($q) use ($date, $currentInstructorId, $query) {
                                $q->whereDoesntHave('lectures', function ($sub) use ($date) {
                                    $sub->whereDate('date', $date);
                                });

                                if ($currentInstructorId) {
                                    $q->orWhere('id', $currentInstructorId);
                                }
                            });
                        }
                    }
                )
                ->preload()
                ->searchable()
                ->required()
                ->rule(function ($attribute, $value, $fail, $get, $livewire) {

                    $date = $get('date');

                    if (! $date || ! $value) {
                        return;
                    }

                    $lectureId = $livewire->record?->id;

                    $exists = Lecture::query()
                        ->where('instructor_id', $value)
                        ->whereDate('date', $date)
                        ->when($lectureId, function ($query) use ($lectureId) {
                            $query->where('id', '!=', $lectureId);
                        })
                        ->exists();

                    if ($exists) {
                        $fail('This instructor is already assigned for the selected date.');
                    }
                }),

            TextInput::make('batch_name'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Course')
                    ->searchable(),

                TextColumn::make('instructor.name')
                    ->label('Instructor')
                    ->searchable(),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),

                TextColumn::make('batch_name'),

            ])->filters([
                SelectFilter::make('course')
                    ->relationship('course', 'name'),

                SelectFilter::make('instructor')
                    ->relationship('instructor', 'name'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLectures::route('/'),
            'create' => CreateLecture::route('/create'),
            'view' => ViewLecture::route('/{record}'),
            'edit' => EditLecture::route('/{record}/edit'),
        ];
    }
    
}
