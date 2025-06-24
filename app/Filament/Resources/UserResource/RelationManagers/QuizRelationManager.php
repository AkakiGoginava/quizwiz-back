<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Rules\MaxQuizPoints;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class QuizRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('points')
                ->required()
                ->numeric()
                ->minValue(0)
                ->rule(fn ($get) => new MaxQuizPoints($get('quiz_id'))),
            Forms\Components\TextInput::make('complete_time')
                ->required()
                ->numeric()
                ->minValue(0)
                ->maxValue(fn ($record) => $record?->max_time)
                ->suffix('Seconds'),
            Forms\Components\DateTimePicker::make('created_at')
                ->required()
                ->label('Completed At'),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('pivot.points')->label('Points'),
                Tables\Columns\TextColumn::make('pivot.complete_time')->formatStateUsing(fn ($state) => gmdate('i:s', $state))->label('Complete Time'),
                Tables\Columns\TextColumn::make('pivot.created_at')->label('Completed At'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        ...$this->getFormSchema(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
