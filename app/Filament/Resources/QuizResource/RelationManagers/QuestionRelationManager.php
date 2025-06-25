<?php

namespace App\Filament\Resources\QuizResource\RelationManagers;

use App\Filament\Resources\Schemas\AnswerRepeaterSchema;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->columnSpan(2)
                    ->required(),
                Forms\Components\Repeater::make('answers')
                    ->relationship('answers')
                    ->schema(AnswerRepeaterSchema::schema())
                    ->rules(AnswerRepeaterSchema::rules())
                    ->collapsed()
                    ->itemLabel(fn ($state) => $state['description'] ?? 'Answer')
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
