<?php

namespace App\Filament\Resources\DifficultyResource\Pages;

use App\Filament\Resources\DifficultyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDifficulty extends CreateRecord
{
    protected static string $resource = DifficultyResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
