<?php

namespace App\Filament\Resources;

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
                    ->label('ゼミ集合写真')
                    ->helperText('ヒーロー右側・本文上部に表示されます。推奨: 横長（16:9 以上）・5 MB 以下。')
                    ->image()
                    ->disk('public')
                    ->directory('theme-photos')
                    ->visibility('public')
                    ->imagePreviewHeight('220')
                    ->maxSize(5 * 1024)
                    ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 5 MB）。'])
                    ->deletable()
                    ->nullable()
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
