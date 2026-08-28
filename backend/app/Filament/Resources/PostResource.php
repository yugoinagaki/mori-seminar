<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = '記事 (News / Blog)';

    protected static ?string $navigationGroup = 'サイトコンテンツ';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('タイトル')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                                $set('slug', Str::slug($state ?? ''))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('スラッグ（URL）')
                            ->required()
                            ->maxLength(255)
                            ->unique(Post::class, 'slug', ignoreRecord: true),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('type')
                                ->label('種別')
                                ->options([
                                    'news'     => 'NEWS（先生の活動）',
                                    'blog'     => 'Blog（学生ブログ）',
                                    'activity' => '活動報告',
                                    'admission'=> '入ゼミ情報',
                                ])
                                ->required()
                                ->default('news'),

                            Forms\Components\Select::make('status')
                                ->label('ステータス')
                                ->options([
                                    'draft'     => '下書き',
                                    'published' => '公開',
                                ])
                                ->required()
                                ->default('draft'),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('公開日時')
                                ->nullable(),
                        ]),
                    ])
                    ->columnSpan(2),

                Forms\Components\Section::make('分類・設定')
                    ->schema([
                        Forms\Components\Select::make('author_id')
                            ->label('投稿者')
                            ->relationship('author', 'name')
                            ->required()
                            ->default(fn () => auth()->id()),

                        Forms\Components\Select::make('categories')
                            ->label('カテゴリ')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload(),

                        Forms\Components\Select::make('tags')
                            ->label('タグ')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('slug')->required(),
                            ]),

                        Forms\Components\FileUpload::make('thumbnail_url')
                            ->label('サムネイル画像')
                            ->image()
                            ->directory('thumbnails')
                            ->nullable(),
                    ])
                    ->columnSpan(1),

                Forms\Components\Section::make('抜粋')
                    ->schema([
                        Forms\Components\Textarea::make('excerpt')
                            ->label('抜粋・概要')
                            ->rows(3)
                            ->maxLength(500)
                            ->nullable()
                            ->helperText('一覧ページに表示されるサマリ文（省略可）'),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('本文')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('post-images')
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(3),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('種別')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'news'      => 'info',
                        'blog'      => 'success',
                        'activity'  => 'warning',
                        'admission' => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'news'      => 'NEWS',
                        'blog'      => 'Blog',
                        'activity'  => '活動報告',
                        'admission' => '入ゼミ',
                        default     => $state,
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('タイトル')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('投稿者')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('状態')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft'     => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => '公開',
                        'draft'     => '下書き',
                        default     => $state,
                    }),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('公開日')
                    ->date('Y.m.d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日')
                    ->date('Y.m.d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('種別')
                    ->options([
                        'news'      => 'NEWS',
                        'blog'      => 'Blog',
                        'activity'  => '活動報告',
                        'admission' => '入ゼミ',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->label('ステータス')
                    ->options([
                        'draft'     => '下書き',
                        'published' => '公開',
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
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
