<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ContactSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'お問い合わせ情報';

    protected static ?string $navigationGroup = 'サイト設定';

    protected static ?int $navigationSort = 101;

    protected static string $view = 'filament.pages.contact-settings';

    protected static ?string $title = 'お問い合わせ情報';

    public array $data = [];

    public function mount(): void
    {
        $setting = SiteSetting::instance();
        $this->form->fill([
            'contact_email'         => $setting->contact_email,
            'contact_twitter_url'   => $setting->contact_twitter_url,
            'contact_instagram_url' => $setting->contact_instagram_url,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('連絡先')
                    ->description('公開サイトの「お問い合わせ」ページとフッターに表示される連絡先です。')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('メールアドレス')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('例: morisemi2020@gmail.com'),

                        TextInput::make('contact_twitter_url')
                            ->label('X (Twitter) URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('例: https://twitter.com/morisemi_keio')
                            ->helperText('URL 末尾からハンドル (@morisemi_keio 等) を自動抽出して表示します。空欄なら X リンクは非表示。'),

                        TextInput::make('contact_instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('例: https://instagram.com/keio.mori')
                            ->helperText('空欄なら Instagram リンクは非表示。'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = SiteSetting::instance();
        $setting->fill($this->form->getState());
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
}
