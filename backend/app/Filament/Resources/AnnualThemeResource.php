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

    protected static ?string $navigationIcon  = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = '年間テーマ';
    protected static ?string $navigationGroup = 'サイトコンテンツ';
    protected static ?int    $navigationSort  = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('year')
                        ->label('年度')
                        ->required()
                        ->numeric(),
                    Forms\Components\Select::make('semester')
                        ->label('期')
                        ->options([
                            'spring' => '春学期',
                            'fall'   => '秋学期',
                        ])
                        ->placeholder('通年（未指定）')
                        ->helperText('同じ年度で春・秋を分ける場合に指定。空欄なら通年テーマ扱い。'),
                ]),
                Forms\Components\TextInput::make('title')
                    ->label('テーマタイトル')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Placeholder::make('_photos_note')
                    ->label('写真')
                    ->content('集合写真とスライドショーは「年度の写真」から年度単位で管理します。')
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
                Tables\Columns\TextColumn::make('semester')
                    ->label('期')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'spring' => '春学期',
                        'fall'   => '秋学期',
                        default  => '通年',
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'spring' => 'success',
                        'fall'   => 'warning',
                        default  => 'gray',
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('テーマ')
                    ->searchable(),
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
