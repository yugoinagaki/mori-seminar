<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PageVisibilitySettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationLabel = 'ページ公開設定';

    protected static ?string $navigationGroup = 'サイト設定';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.page-visibility-settings';

    protected static ?string $title = 'ページ公開設定';

    public array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::instance();
        $current = $setting->page_visibilities ?? [];

        $state = [];
        foreach (array_keys(SiteSetting::PAGE_KEYS) as $key) {
            $state[$key] = $current[$key] ?? true;
        }

        $this->form->fill($state);
    }

    public function form(Form $form): Form
    {
        $toggles = [];
        foreach (SiteSetting::PAGE_KEYS as $key => $label) {
            $toggles[] = Toggle::make($key)
                ->label($label)
                ->helperText($this->helperFor($key))
                ->inline(false);
        }

        return $form
            ->schema([
                Section::make('公開するページ')
                    ->description('OFF にしたページは公開サイトで 404 になり、ナビゲーションからも消えます。トップページは常に公開です。')
                    ->schema($toggles)
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = SiteSetting::instance();
        $setting->page_visibilities = $this->form->getState();
        $setting->save();

        Notification::make()
            ->title('保存しました')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存')
                ->submit('save'),
        ];
    }

    private function helperFor(string $key): string
    {
        return match ($key) {
            'news'         => '/news 一覧と /news/{slug} の個別記事',
            'blog'         => '/blog 一覧と /blog/{slug} の個別記事',
            'case_studies' => '/case-studies 一覧と /case-studies/{slug} の個別記事',
            default        => "/{$key} ページ",
        };
    }
}
