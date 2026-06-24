<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'サイト設定';
    protected static ?string $navigationGroup = '設定';
    protected static ?string $title           = 'サイト設定';
    protected static ?int    $navigationSort  = 99;

    protected static string $view = 'filament.pages.site-settings-page';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::instance();

        // FileUpload は配列形式で値を受け取る必要がある
        $this->form->fill([
            'hero_image_url' => $setting->hero_image_url
                ? [$setting->hero_image_url]
                : [],
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('トップページ ヒーロー画像')
                    ->description('背景に表示する写真をアップロードしてください。推奨サイズ: 1920×1080px 以上')
                    ->schema([
                        Forms\Components\FileUpload::make('hero_image_url')
                            ->label('ヒーロー背景画像')
                            ->image()
                            ->disk('public')
                            ->directory('hero')
                            ->visibility('public')
                            ->imagePreviewHeight('300')
                            ->deletable()
                            ->nullable()
                            ->helperText('JPG・PNG・WebP対応。✕ボタンで削除するとグラデーション背景に戻ります。'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // FileUpload は連想配列（ハッシュキー）で返すので reset で先頭値を取り出す
        $raw = $state['hero_image_url'] ?? null;
        if (is_array($raw)) {
            $raw = reset($raw) ?: null;
        }

        SiteSetting::instance()->update(['hero_image_url' => $raw]);

        Notification::make()
            ->title('設定を保存しました')
            ->success()
            ->send();
    }
}
