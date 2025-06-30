<?php

namespace App\Filament\Resources\Schemas;

use Closure;
use Filament\Forms;
use Filament\Forms\Get;

class AnswerFormSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('description')
                ->required()
                ->maxLength(255),
            Forms\Components\Toggle::make('is_correct')
                ->label('Correct Answer')
                ->required(),
        ];
    }

    public static function rules(): array
    {
        return [
            fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                $answers = $get('answers') ?? [];

                $hasCorrect = collect($answers)->where('is_correct', true)->count() > 0;
                $hasWrong = collect($answers)->where('is_correct', false)->count() > 0;

                if (! $hasCorrect || ! $hasWrong) {
                    $fail('Each question must have at least one correct and one wrong answer.');
                }
            },
        ];
    }
}
