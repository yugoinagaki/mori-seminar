<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $navigationGroup = 'サイトコンテンツ';

    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category')
                    ->label('カテゴリ')
                    ->options([
                        '入ゼミ' => '入ゼミ',
                        '活動'   => '活動',
                        '選考'   => '選考',
                        'その他' => 'その他',
                    ])
                    ->nullable(),

                Forms\Components\TextInput::make('order_index')
                    ->label('表示順')
                    ->numeric()
                    ->minValue(0)
                    ->step(1)
                    ->default(0)
                    ->helperText('0以上の整数。小さい順に上から表示されます。'),

                Forms\Components\Toggle::make('is_active')
                    ->label('表示する')
                    ->default(true),

                Forms\Components\Textarea::make('question')
                    ->label('質問')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('answer')
                    ->label('回答')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('カテゴリ')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('question')
                    ->label('質問')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('表示')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order_index')
                    ->label('順')
                    ->sortable(),
            ])
            ->defaultSort('order_index')
            ->reorderable('order_index')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        '入ゼミ' => '入ゼミ',
                        '活動'   => '活動',
                        '選考'   => '選考',
                        'その他' => 'その他',
                    ]),
            ])
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
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
