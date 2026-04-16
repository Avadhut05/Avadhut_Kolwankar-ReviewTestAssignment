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
                    ->live()
                    ->rule(function ($attribute, $value, $fail, $get, $livewire) {
                        if (! $value) {
                            return;
                        }

                        $instructorId = $this->ownerRecord->id;
                        $lectureId = $livewire->mountedTableActionRecord ?? null;

                        $exists = Lecture::query()
                            ->where('instructor_id', $instructorId)
                            ->whereDate('date', $value)
                            ->when($lectureId, function ($query) use ($lectureId) {
                                $query->where('id', '!=', $lectureId);
                            })
                            ->exists();

                        if ($exists) {
                            $fail('This instructor already has a lecture assigned on the selected date.');
                        }
                    }),

                TimePicker::make('start_time')
                    ->seconds(false)
                    ->required(),

                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

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
                    ->time('h:i A')
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
