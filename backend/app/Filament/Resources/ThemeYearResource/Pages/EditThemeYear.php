<?php

namespace App\Filament\Resources\ThemeYearResource\Pages;

use App\Filament\Resources\ThemeYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThemeYear extends EditRecord
{
    protected static string $resource = ThemeYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
