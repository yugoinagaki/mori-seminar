<?php

namespace App\Filament\Actions;

use App\Models\MediaFile;
use Filament\Forms;
use Filament\Forms\Set;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;

class MediaPickerAction
{
    /**
     * FileUpload の hintAction として使う「ライブラリから選ぶ」アクション。
     *
     * @param string      $fieldName  セットしたいフォームフィールド名
     * @param string|null $collection 絞り込む collection（null = 全件）
     * @param bool        $multiple   複数選択モード（slideshow 等）
     */
    public static function make(string $fieldName, ?string $collection = null, bool $multiple = false): \Filament\Forms\Components\Actions\Action
    {
        return \Filament\Forms\Components\Actions\Action::make("media_picker_{$fieldName}")
            ->label('ライブラリから選ぶ')
            ->icon('heroicon-o-archive-box')
            ->modalHeading('メディアライブラリ')
            ->modalWidth(MaxWidth::ThreeExtraLarge)
            ->form(function () use ($collection, $multiple) {
                $query = MediaFile::orderByDesc('created_at');
                if ($collection) {
                    $query->where('collection', $collection);
                }
                $options = $query->get()->mapWithKeys(
                    fn ($m) => [$m->path => "[{$m->collection}]  {$m->original_name}  ({$m->humanSize()})"]
                );

                $fields = [
                    Forms\Components\Select::make('selected_paths')
                        ->label($multiple ? 'ファイルを選択（複数可）' : 'ファイルを選択')
                        ->options($options)
                        ->multiple($multiple)
                        ->searchable()
                        ->required()
                        ->reactive(),
                ];

                // 画像プレビュー
                $fields[] = Forms\Components\Placeholder::make('preview')
                    ->label('プレビュー')
                    ->content(function (Forms\Get $get) use ($multiple) {
                        $paths = $multiple
                            ? (array) ($get('selected_paths') ?? [])
                            : array_filter([$get('selected_paths')]);

                        if (empty($paths)) return new HtmlString('<p class="text-sm text-gray-400">ファイルを選択するとプレビューが表示されます</p>');

                        $imgs = collect($paths)->map(function ($path) {
                            $media = MediaFile::where('path', $path)->first();
                            if (!$media) return '';
                            if ($media->isImage()) {
                                return '<img src="' . e($media->url()) . '" class="h-28 w-auto rounded object-cover border border-gray-200">';
                            }
                            return '<div class="flex items-center gap-2 text-sm text-gray-600"><span>📄</span>' . e($media->original_name) . '</div>';
                        })->implode('');

                        return new HtmlString('<div class="flex flex-wrap gap-3 mt-1">' . $imgs . '</div>');
                    })
                    ->hidden(fn (Forms\Get $get) => empty($get('selected_paths')));

                return $fields;
            })
            ->action(function (array $data, Set $set) use ($fieldName, $multiple) {
                $selected = $data['selected_paths'] ?? null;
                if ($multiple) {
                    $current = $set($fieldName, array_values(array_filter((array) $selected)));
                } else {
                    $set($fieldName, is_array($selected) ? (reset($selected) ?: null) : $selected);
                }
            });
    }
}
