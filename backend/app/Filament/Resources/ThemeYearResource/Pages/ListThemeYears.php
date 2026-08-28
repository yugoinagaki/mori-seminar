<?php

namespace App\Filament\Resources\ThemeYearResource\Pages;

use App\Filament\Resources\ThemeYearResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThemeYears extends ListRecords
{
    protected static string $resource = ThemeYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
