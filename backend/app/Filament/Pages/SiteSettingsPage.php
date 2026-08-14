<?php

namespace App\Filament\Pages;

use App\Filament\Actions\MediaPickerAction;
use App\Models\MediaFile;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public array $transition1 = [];
    public array $transition2 = [];
    public array $transition3 = [];

    protected function getForms(): array
    {
        return ['heroForm', 'transitionForm'];
    }

    public function mount(): void
    {
        $setting  = SiteSetting::instance();
        $existing = $setting->transition_image_urls ?? [];

        $this->heroForm->fill([
            'hero_image_url' => $setting->hero_image_url ? [$setting->hero_image_url] : [],
        ]);

        $this->transitionForm->fill([
            'transition1' => isset($existing[0]) ? [$existing[0]] : [],
            'transition2' => isset($existing[1]) ? [$existing[1]] : [],
            'transition3' => isset($existing[2]) ? [$existing[2]] : [],
        ]);
    }

    public function heroForm(Form $form): Form
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
                            ->maxSize(5 * 1024)
                            ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 5 MB）。圧縮してから再度アップロードしてください。'])
                            ->deletable()
                            ->nullable()
                            ->hintAction(MediaPickerAction::make('hero_image_url', 'hero'))
                            ->helperText('JPG・PNG・WebP対応。上限 5 MB。✕ボタンで削除するとグラデーション背景に戻ります。'),
                    ]),
            ])
            ->statePath('data');
    }

    public function transitionForm(Form $form): Form
    {
        $slot = fn(string $name, string $label) =>
            Forms\Components\FileUpload::make($name)
                ->label($label)
                ->image()
                ->disk('public')
                ->directory('transitions')
                ->visibility('public')
                ->imagePreviewHeight('180')
                ->maxSize(5 * 1024)
                ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 5 MB）。圧縮してから再度アップロードしてください。'])
                ->deletable()
                ->nullable()
                ->hintAction(MediaPickerAction::make($name, 'transition'));

        return $form
            ->schema([
                Forms\Components\Section::make('ページ遷移アニメーション画像')
                    ->description('ページ移動時のワイプアニメーション背景画像を最大3枚登録できます。毎回ランダムに切り替わります。未設定時は forest.png を使用します。JPG・PNG・WebP対応。推奨サイズ: 1920×1080px 以上。')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                $slot('transition1', '画像1'),
                                $slot('transition2', '画像2'),
                                $slot('transition3', '画像3'),
                            ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        $setting = SiteSetting::instance();
        $oldHero        = $setting->hero_image_url;
        $oldTransitions = $setting->transition_image_urls ?? [];

        // getState() moves any TemporaryUploadedFile to permanent storage.
        // But for deletion detection we rely on the raw Livewire property being empty,
        // because getState() may return the original path even after the user clicks ✕.
        $heroState  = $this->heroForm->getState();
        $transState = $this->transitionForm->getState();

        // Hero: raw property empty → user deleted it
        $hero = null;
        if (!empty($this->data['hero_image_url'] ?? [])) {
            $v = $heroState['hero_image_url'] ?? null;
            $hero = is_array($v) ? (reset($v) ?: null) : ($v ?: null);
        }

        // Transitions: same — only include slots whose raw property is non-empty
        $transitions = [];
        $rawSlots = [
            'transition1' => $this->transition1,
            'transition2' => $this->transition2,
            'transition3' => $this->transition3,
        ];
        foreach ($rawSlots as $key => $raw) {
            if (empty($raw)) {
                continue;
            }
            $v = $transState[$key] ?? null;
            if (is_array($v)) {
                $v = reset($v) ?: null;
            }
            if ($v) {
                $transitions[] = $v;
            }
        }

        DB::transaction(function () use ($setting, $hero, $transitions) {
            $setting->update([
                'hero_image_url'        => $hero,
                'transition_image_urls' => $transitions ?: null,
            ]);

            if ($hero) MediaFile::track($hero, 'hero');
            foreach ($transitions as $path) {
                MediaFile::track($path, 'transition');
            }
        });

        // Delete orphaned files from storage
        if ($oldHero && $hero !== $oldHero) {
            Storage::disk('public')->delete($oldHero);
        }
        foreach ($oldTransitions as $oldPath) {
            if ($oldPath && !in_array($oldPath, $transitions, true)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        Notification::make()
            ->title('設定を保存しました')
            ->success()
            ->send();
    }
}
