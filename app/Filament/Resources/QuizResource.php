<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Filament\Resources\Schemas\AnswerRepeaterSchema;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_users')
                    ->required()
                    ->default(0)
                    ->numeric(),
                Forms\Components\Select::make('difficulty_id')
                    ->relationship('difficulty', 'name')
                    ->preload()
                    ->createOptionForm(DifficultyResource::getFormSchema())
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('instructions')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('max_time')
                    ->required()
                    ->numeric()
                    ->suffix('Seconds'),
                Forms\Components\Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload()
                    ->createOptionForm(CategoryResource::getFormSchema())
                    ->required(),
                Forms\Components\Repeater::make('questions')
                    ->relationship('questions')
                    ->visibleOn('create')
                    ->schema([
                        Forms\Components\TextInput::make('description')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Repeater::make('answers')
                            ->relationship('answers')
                            ->schema(AnswerRepeaterSchema::schema())
                            ->rules(AnswerRepeaterSchema::rules())
                            ->collapsed()
                            ->itemLabel(fn ($state) => $state['description'] ?? 'Answer')
                            ->minItems(2)
                            ->required(),
                    ])
                    ->collapsed()
                    ->itemLabel(fn ($state) => $state['description'] ?? 'Question')
                    ->columnSpan(2)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_users')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('difficulty.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('max_time')->formatStateUsing(fn ($state) => gmdate('i:s', $state)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit'   => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
