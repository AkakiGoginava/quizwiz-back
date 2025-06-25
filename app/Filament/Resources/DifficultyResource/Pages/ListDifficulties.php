<?php

namespace App\Filament\Resources\DifficultyResource\Pages;

use App\Filament\Resources\DifficultyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDifficulties extends ListRecords
{
    protected static string $resource = DifficultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
