<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CohortResource\Pages;
use App\Models\Cohort;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CohortResource extends Resource
{
    protected static ?string $model = Cohort::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = '期 (Cohort)';

    protected static ?string $navigationGroup = 'サイトコンテンツ';

    protected static ?int $navigationSort = 41;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('generation')
                    ->label('期（例: 3）')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('半角数字。同じ期の重複はできません。'),

                Forms\Components\Toggle::make('visible')
                    ->label('ゼミ生紹介ページに公開')
                    ->helperText('オフにするとゼミ生紹介ページのタブと一覧から非表示になります。ブログ記事は期に関わらずずっと公開されます。')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('members'))
            ->columns([
                Tables\Columns\TextColumn::make('generation')
                    ->label('期')
                    ->formatStateUsing(fn ($state) => "{$state}期")
                    ->sortable(),

                Tables\Columns\TextColumn::make('members_count')
                    ->label('所属人数')
                    ->formatStateUsing(fn ($state) => "{$state}人"),

                Tables\Columns\ToggleColumn::make('visible')
                    ->label('公開')
                    ->tooltip('ゼミ生紹介ページのみ対象。ブログは期に関わらず常に公開されます。')
                    ->grow(false),
            ])
            ->defaultSort('generation', 'desc')
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCohorts::route('/'),
            'create' => Pages\CreateCohort::route('/create'),
            'edit'   => Pages\EditCohort::route('/{record}/edit'),
        ];
    }
}
