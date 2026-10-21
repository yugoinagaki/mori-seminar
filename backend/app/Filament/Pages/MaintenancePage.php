<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MaintenancePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = 'メンテナンスモード';

    protected static ?string $navigationGroup = 'サイト設定';

    protected static ?int $navigationSort = 20;

    protected static string $view = 'filament.pages.maintenance-page';

    protected static ?string $title = 'メンテナンスモード';

    public bool $isMaintenance = false;

    public function mount(): void
    {
        $this->isMaintenance = file_exists(public_path('maintenance.flag'));
    }

    public function enable(): void
    {
        file_put_contents(public_path('maintenance.flag'), '1');
        $this->isMaintenance = true;

        Notification::make()
            ->title('メンテナンスモードを有効にしました')
            ->success()
            ->send();
    }

    public function disable(): void
    {
        @unlink(public_path('maintenance.flag'));
        $this->isMaintenance = false;

        Notification::make()
            ->title('メンテナンスモードを解除しました')
            ->success()
            ->send();
    }
}
