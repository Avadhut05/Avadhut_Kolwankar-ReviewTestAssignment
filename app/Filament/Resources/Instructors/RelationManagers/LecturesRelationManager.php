<?php

namespace App\Filament\Resources\Instructors\RelationManagers;

use App\Models\Lecture;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LecturesRelationManager extends RelationManager
{
    protected static string $relationship = 'lectures';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required()
                    ->live(),

                TimePicker::make('start_time')
                    ->seconds(false)
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

    public function table(Table $table): Table
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
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]);
    }
}
