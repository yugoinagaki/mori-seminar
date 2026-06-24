<?php

namespace App\Filament\Resources\AnnualThemeResource\Pages;

use App\Filament\Resources\AnnualThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnnualTheme extends EditRecord
{
    protected static string $resource = AnnualThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
