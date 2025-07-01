<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DifficultyResource\Pages;
use App\Models\Difficulty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DifficultyResource extends Resource
{
    protected static ?string $model = Difficulty::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('icon')
                ->image()
                ->multiple(false)
                ->dehydrateStateUsing(fn ($state, $record) =>
                    is_array($state)
                        ? (count($state) > 0 ? array_values($state)[0] : ($record?->icon ?? null))
                        : ($state ?: ($record?->icon ?? null))
                )
                ->required(fn ($livewire) => $livewire instanceof \App\Filament\Resources\DifficultyResource\Pages\CreateDifficulty)
                ->nullable(fn ($livewire) => $livewire instanceof \App\Filament\Resources\DifficultyResource\Pages\EditDifficulty), 
            Forms\Components\ColorPicker::make('color')
                ->required(),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema());
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('icon'),
                Tables\Columns\ColorColumn::make('color')
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDifficulties::route('/'),
            'create' => Pages\CreateDifficulty::route('/create'),
            'edit'   => Pages\EditDifficulty::route('/{record}/edit'),
        ];
    }
}
