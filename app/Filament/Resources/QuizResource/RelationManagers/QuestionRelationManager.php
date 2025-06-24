<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Repeater::make('answers')
                    ->relationship('answers')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_correct')
                            ->label('Correct Answer')
                            ->required(),
                    ])
                    ->collapsed()
                    ->rules([fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                        $answers = $get('answers') ?? [];

                        $hasCorrect = collect($answers)->where('is_correct', true)->count() > 0;
                        $hasWrong = collect($answers)->where('is_correct', false)->count() > 0;

                        if (! $hasCorrect || ! $hasWrong) {
                            $fail('Each question must have at least one correct and one wrong answer.');
                        }
                    }])
                    ->minItems(2)
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
