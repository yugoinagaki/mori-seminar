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

    protected static ?string $navigationLabel = 'メンバー管理';

    protected static ?string $navigationGroup = 'コンテンツ';

    protected static ?int $navigationSort = 2;

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

                            Forms\Components\TextInput::make('name_kana')
                                ->label('氏名（かな）')
                                ->maxLength(100),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('generation')
                                ->label('期（例: 3）')
                                ->numeric()
                                ->nullable(),

                            Forms\Components\TextInput::make('university_year')
                                ->label('学年（1〜4）')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(4)
                                ->nullable(),

                            Forms\Components\TextInput::make('major')
                                ->label('専攻・学科')
                                ->maxLength(100)
                                ->nullable(),
                        ]),

                        Forms\Components\Textarea::make('bio')
                            ->label('自己紹介')
                            ->rows(4)
                            ->nullable(),
                    ])
                    ->columnSpan(2),

                Forms\Components\Section::make('ステータス・設定')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_image_url')
                            ->label('プロフィール画像')
                            ->image()
                            ->directory('members')
                            ->nullable(),

                        Forms\Components\Select::make('status')
                            ->label('ステータス')
                            ->options([
                                'active' => '現役',
                                'ob_og'  => 'OB/OG',
                            ])
                            ->required()
                            ->default('active'),

                        Forms\Components\TextInput::make('graduated_year')
                            ->label('卒業年度')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\TextInput::make('order_index')
                            ->label('表示順')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columnSpan(1),

                Forms\Components\Section::make('SNSリンク')
                    ->schema([
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('X (Twitter) URL')
                            ->url()
                            ->nullable(),

                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->nullable(),
                    ])
                    ->columnSpan(3),
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

                Tables\Columns\TextColumn::make('generation')
                    ->label('期')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}期" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('university_year')
                    ->label('学年')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}年" : '-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('ステータス')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'ob_og'  => 'gray',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => '現役',
                        'ob_og'  => 'OB/OG',
                        default  => $state,
                    }),

                Tables\Columns\TextColumn::make('order_index')
                    ->label('表示順')
                    ->sortable(),
            ])
            ->defaultSort('order_index')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('ステータス')
                    ->options([
                        'active' => '現役',
                        'ob_og'  => 'OB/OG',
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
