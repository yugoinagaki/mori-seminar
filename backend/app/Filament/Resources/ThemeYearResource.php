<?php

namespace App\Filament\Resources;

use App\Filament\Actions\MediaPickerAction;
use App\Filament\Resources\ThemeYearResource\Pages;
use App\Models\ThemeYear;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ThemeYearResource extends Resource
{
    protected static ?string $model = ThemeYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = '年度の写真';

    protected static ?string $navigationGroup = 'サイトコンテンツ';

    protected static ?string $modelLabel = '年度の写真';

    protected static ?string $pluralModelLabel = '年度の写真';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->label('年度')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100)
                    ->step(1)
                    ->unique(ignoreRecord: true)
                    ->helperText('この年度のテーマページとトップページで使う写真をまとめて管理します。'),

                Forms\Components\FileUpload::make('photo_url')
                    ->label('テーマページ用 集合写真（1枚）')
                    ->helperText('/theme ページ上部に表示。推奨: 横長（16:9 以上）・5 MB 以下。')
                    ->image()
                    ->disk('public')
                    ->directory('theme-photos')
                    ->visibility('public')
                    ->imagePreviewHeight('180')
                    ->maxSize(5 * 1024)
                    ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 5 MB）。'])
                    ->deletable()
                    ->nullable()
                    ->hintAction(MediaPickerAction::make('photo_url'))
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('slideshow_photo_urls')
                    ->label('トップページ用 スライドショー写真（最大10枚）')
                    ->helperText('トップページのテーマセクション右側にスライドショー表示。各 5 MB 以下。')
                    ->image()
                    ->multiple()
                    ->maxFiles(10)
                    ->disk('public')
                    ->directory('theme-slideshow')
                    ->visibility('public')
                    ->imagePreviewHeight('140')
                    ->maxSize(5 * 1024)
                    ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 5 MB）。'])
                    ->reorderable()
                    ->deletable()
                    ->nullable()
                    ->hintAction(MediaPickerAction::make('slideshow_photo_urls', null, true))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('年度')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('集合写真')
                    ->disk('public')
                    ->height(48)
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('slideshow_photo_urls')
                    ->label('スライドショー')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . '枚' : '-'),
            ])
            ->defaultSort('year', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListThemeYears::route('/'),
            'create' => Pages\CreateThemeYear::route('/create'),
            'edit'   => Pages\EditThemeYear::route('/{record}/edit'),
        ];
    }
}
