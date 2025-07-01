<?php

namespace App\Filament\Resources\DifficultyResource\Pages;

use App\Filament\Resources\DifficultyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDifficulty extends EditRecord
{
    protected static string $resource = DifficultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
