<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaFileResource\Pages;
use App\Models\MediaFile;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MediaFileResource extends Resource
{
    protected static ?string $model           = MediaFile::class;
    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'メディア管理';
    protected static ?string $navigationGroup = '設定';
    protected static ?string $pluralLabel     = 'メディア管理';
    protected static ?string $label           = 'メディアファイル';
    protected static ?int    $navigationSort  = 50;

    public static function table(Table $table): Table
    {
        $collections = MediaFile::distinct()->orderBy('collection')->pluck('collection', 'collection')->toArray();

        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('プレビュー')
                    ->disk('public')
                    ->height(80)
                    ->width(120)
                    ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:4px'])
                    ->defaultImageUrl(asset('images/file-icon.svg')),
                Tables\Columns\TextColumn::make('original_name')
                    ->label('ファイル名')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\BadgeColumn::make('collection')
                    ->label('アップロード元')
                    ->colors([
                        'primary'   => 'hero',
                        'success'   => 'slideshow',
                        'warning'   => 'transition',
                        'gray'      => 'theme-photo',
                        'danger'    => fn ($state) => !in_array($state, ['hero', 'slideshow', 'transition', 'theme-photo']),
                    ]),
                Tables\Columns\TextColumn::make('size')
                    ->label('サイズ')
                    ->formatStateUsing(fn ($state, $record) => $record->humanSize()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('登録日時')
                    ->dateTime('Y/m/d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('collection')
                    ->label('アップロード元')
                    ->options($collections),
                Tables\Filters\SelectFilter::make('type')
                    ->label('種別')
                    ->options(['image' => '画像', 'pdf' => 'PDF', 'other' => 'その他'])
                    ->query(fn ($query, $data) => match ($data['value'] ?? null) {
                        'image' => $query->where('mime_type', 'like', 'image/%'),
                        'pdf'   => $query->where('mime_type', 'application/pdf'),
                        'other' => $query->where('mime_type', 'not like', 'image/%')->where('mime_type', '!=', 'application/pdf'),
                        default => $query,
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('copy_url')
                    ->label('URLコピー')
                    ->icon('heroicon-o-clipboard')
                    ->action(function ($record, $livewire) {
                        $livewire->dispatch('copy-to-clipboard', url: $record->url());
                        Notification::make()->title('URLをコピーしました')->success()->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('削除')
                    ->requiresConfirmation()
                    ->modalDescription('ファイルをストレージから完全に削除します。この操作は取り消せません。')
                    ->before(fn ($record) => Storage::disk($record->disk)->delete($record->path)),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('選択したファイルを一括削除')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('選択したファイルをストレージから完全に削除します。この操作は取り消せません。')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            Storage::disk($record->disk)->delete($record->path);
                            $record->delete();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMediaFiles::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
