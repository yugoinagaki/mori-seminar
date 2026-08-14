<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaFileResource\Pages;
use App\Models\MediaFile;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->label('')
                    ->disk('public')
                    ->height(160)
                    ->width('100%')
                    ->extraImgAttributes(['style' => 'width:100%;height:160px;object-fit:cover;border-radius:6px 6px 0 0'])
                    ->defaultImageUrl(asset('images/file-icon.svg')),
                Tables\Columns\TextColumn::make('original_name')
                    ->label('ファイル名')
                    ->searchable()
                    ->limit(28)
                    ->weight('medium'),
                Tables\Columns\BadgeColumn::make('collection')
                    ->label('')
                    ->colors([
                        'primary'   => 'hero',
                        'success'   => 'slideshow',
                        'warning'   => 'transition',
                        'gray'      => 'theme-photo',
                        'danger'    => fn ($state) => !in_array($state, ['hero', 'slideshow', 'transition', 'theme-photo']),
                    ]),
                Tables\Columns\TextColumn::make('size')
                    ->label('')
                    ->formatStateUsing(fn ($state, $record) => $record->humanSize())
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('')
                    ->dateTime('Y/m/d H:i')
                    ->sortable()
                    ->color('gray'),
            ])
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'xl' => 4,
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
                    ->action(fn () => null)
                    ->extraAttributes(fn ($record) => [
                        'x-data' => '',
                        'x-on:click' => "navigator.clipboard.writeText('" . addslashes($record->url()) . "').then(() => window.\$notification?.success('URLをコピーしました'))",
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->label('削除')
                    ->requiresConfirmation()
                    ->modalDescription('メディアライブラリから削除します。ファイル自体はサーバーに残ります。'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('選択を削除'),
                ]),
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
