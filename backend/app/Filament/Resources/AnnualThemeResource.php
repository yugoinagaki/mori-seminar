<?php

namespace App\Filament\Resources;

use App\Filament\Actions\MediaPickerAction;
use App\Filament\Resources\AnnualThemeResource\Pages;
use App\Filament\Resources\AnnualThemeResource\RelationManagers;
use App\Models\AnnualTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnualThemeResource extends Resource
{
    protected static ?string $model = AnnualTheme::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->label('年度')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->label('テーマタイトル')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
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
                Forms\Components\Textarea::make('content')
                    ->label('本文')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('年度')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('テーマ')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('集合写真')
                    ->disk('public')
                    ->height(48)
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnualThemes::route('/'),
            'create' => Pages\CreateAnnualTheme::route('/create'),
            'edit' => Pages\EditAnnualTheme::route('/{record}/edit'),
        ];
    }
}
