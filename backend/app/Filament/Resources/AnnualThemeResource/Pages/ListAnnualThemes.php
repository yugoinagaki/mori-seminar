<?php

namespace App\Filament\Resources\AnnualThemeResource\Pages;

use App\Filament\Resources\AnnualThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnnualThemes extends ListRecords
{
    protected static string $resource = AnnualThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
