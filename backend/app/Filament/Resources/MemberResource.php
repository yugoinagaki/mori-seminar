<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'ゼミ生';

    protected static ?string $navigationGroup = 'サイトコンテンツ';

    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('氏名')
                                ->required()
                                ->maxLength(100),

                            Forms\Components\Select::make('cohort_id')
                                ->label('期')
                                ->relationship('cohort', 'generation')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->generation}期")
                                ->searchable()
                                ->preload()
                                ->nullable()
                                ->helperText('先に「期の管理」で作成した期から選択'),
                        ]),

                        Forms\Components\TextInput::make('position')
                            ->label('役職')
                            ->maxLength(100)
                            ->nullable(),

                        Forms\Components\Textarea::make('bio')
                            ->label('自己紹介')
                            ->rows(4)
                            ->nullable(),
                    ])
                    ->columnSpan(2),

                Forms\Components\Section::make('表示設定')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image_url')
                            ->label('プロフィール画像')
                            ->image()
                            ->directory('members')
                            ->nullable(),

                        Forms\Components\TextInput::make('order_index')
                            ->label('表示順')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_image_url')
                    ->label('')
                    ->circular()
                    ->size(40),

                Tables\Columns\TextColumn::make('name')
                    ->label('氏名')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cohort.generation')
                    ->label('期')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}期" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('position')
                    ->label('役職')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('order_index')
                    ->label('表示順')
                    ->sortable(),
            ])
            ->defaultSort('order_index')
            ->filters([
                Tables\Filters\SelectFilter::make('cohort_id')
                    ->label('期')
                    ->relationship('cohort', 'generation')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->generation}期"),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit'   => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
