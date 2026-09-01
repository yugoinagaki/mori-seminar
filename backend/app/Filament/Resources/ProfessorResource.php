<?php

namespace App\Filament\Resources;

use App\Filament\Actions\MediaPickerAction;
use App\Filament\Resources\ProfessorResource\Pages;
use App\Models\Professor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProfessorResource extends Resource
{
    protected static ?string $model = Professor::class;

    protected static ?string $navigationIcon  = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = '教授プロフィール';
    protected static ?string $navigationGroup = 'サイトコンテンツ';
    protected static ?int    $navigationSort  = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('基本情報')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('氏名（日本語）')
                                ->required()
                                ->default('森 聡')
                                ->maxLength(100),

                            Forms\Components\TextInput::make('name_en')
                                ->label('氏名（英語）')
                                ->default('Satoshi Mori')
                                ->maxLength(100),
                        ]),

                        Forms\Components\TextInput::make('title')
                            ->label('肩書き')
                            ->default('慶應義塾大学 法学部 教授')
                            ->maxLength(255),

                        Forms\Components\FileUpload::make('profile_image_url')
                            ->label('プロフィール画像')
                            ->image()
                            ->directory('professor')
                            ->imagePreviewHeight('200')
                            ->nullable(),
                    ])
                    ->columnSpan(2),

                Forms\Components\Section::make('研究テーマ')
                    ->description('段落・箇条書き・強調が使えます。旧タグ形式(research_themes)は本文が空の時のフォールバック。')
                    ->schema([
                        Forms\Components\RichEditor::make('research_themes_body')
                            ->label('')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline',
                                'bulletList', 'orderedList',
                                'link',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\TagsInput::make('research_themes')
                            ->label('（旧）タグ形式')
                            ->placeholder('例: 国際政治学')
                            ->helperText('本文を入力するとこちらは公開ページで使われなくなります。')
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('紹介文 (ブロック)')
                    ->description('見出しと本文をセットで複数ブロック登録できます。ブロックが1つもない時は下の「プロフィール本文 (旧)」が表示されます。')
                    ->schema([
                        Forms\Components\Repeater::make('bio_blocks')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('heading')
                                    ->label('見出し')
                                    ->maxLength(100)
                                    ->placeholder('例: Profile / Career / Present'),
                                Forms\Components\RichEditor::make('body')
                                    ->label('本文')
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'strike',
                                        'h2', 'h3',
                                        'bulletList', 'orderedList',
                                        'link', 'blockquote',
                                        'undo', 'redo',
                                    ])
                                    ->columnSpanFull(),
                            ])
                            ->itemLabel(fn (array $state): string => $state['heading'] ?? '（見出し未入力）')
                            ->addActionLabel('ブロックを追加')
                            ->reorderable()
                            ->collapsible()
                            ->collapsed()
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('プロフィール本文 (旧・フォールバック)')
                    ->description('上の「紹介文 (ブロック)」を1件以上入れると、こちらは公開ページで使われなくなります。')
                    ->schema([
                        Forms\Components\RichEditor::make('bio')
                            ->label('')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'link', 'blockquote',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpan(3),

                Forms\Components\Section::make('経歴')
                    ->description('年号と説明をセットで追加してください')
                    ->schema([
                        Forms\Components\Repeater::make('career')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('year')
                                    ->label('年')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('description')
                                    ->label('内容')
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(4)
                            ->addActionLabel('経歴を追加')
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('受賞歴')
                    ->schema([
                        Forms\Components\Repeater::make('awards')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('year')
                                    ->label('年')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->label('賞名')
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(4)
                            ->addActionLabel('受賞歴を追加')
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('著書')
                    ->description('ドラッグで並び替え可能。表紙画像・タイトル・出版年・出版社・説明文を入力してください。')
                    ->schema([
                        Forms\Components\Repeater::make('books')
                            ->label('')
                            ->schema([
                                Forms\Components\Grid::make(4)->schema([
                                    Forms\Components\FileUpload::make('cover_url')
                                        ->label('表紙画像')
                                        ->image()
                                        ->disk('public')
                                        ->directory('professor/books')
                                        ->imagePreviewHeight('140')
                                        ->maxSize(5 * 1024)
                                        ->nullable()
                                        ->hintAction(MediaPickerAction::make('cover_url')),
                                    Forms\Components\TextInput::make('title')
                                        ->label('タイトル')
                                        ->required()
                                        ->columnSpan(3),
                                    Forms\Components\TextInput::make('year')
                                        ->label('出版年')
                                        ->numeric()
                                        ->minValue(1900)
                                        ->maxValue(2099),
                                    Forms\Components\TextInput::make('publisher')
                                        ->label('出版社')
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('url')
                                        ->label('リンク（Amazon等）')
                                        ->url()
                                        ->placeholder('https://...')
                                        ->columnSpan(1),
                                    Forms\Components\Textarea::make('description')
                                        ->label('説明文')
                                        ->rows(2)
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->itemLabel(fn (array $state): string => $state['title'] ?? '（タイトル未入力）')
                            ->addActionLabel('著書・論文を追加')
                            ->reorderable()
                            ->collapsible()
                            ->collapsed()
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('論文')
                    ->description('表紙画像なしのシンプルなリスト表示。タイトル・掲載誌・出版年・リンク・要旨を入力。')
                    ->schema([
                        Forms\Components\Repeater::make('papers')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('タイトル')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('journal')
                                        ->label('掲載誌 / 出版社')
                                        ->placeholder('例: 東洋経済新報社 / 国際政治')
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('year')
                                        ->label('出版年')
                                        ->numeric()
                                        ->minValue(1900)
                                        ->maxValue(2099)
                                        ->step(1)
                                        ->columnSpan(1),
                                ]),
                                Forms\Components\TextInput::make('url')
                                    ->label('リンク (任意)')
                                    ->url()
                                    ->placeholder('https://...')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('description')
                                    ->label('要旨 (任意)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->itemLabel(fn (array $state): string => $state['title'] ?? '（タイトル未入力）')
                            ->addActionLabel('論文を追加')
                            ->reorderable()
                            ->collapsible()
                            ->collapsed()
                            ->nullable(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('業績一覧 (PDFリンク)')
                    ->description('PDFをアップロードすると公開ページに「業績一覧 (PDF) →」のリンクが表示されます。')
                    ->schema([
                        Forms\Components\FileUpload::make('achievements_pdf_url')
                            ->label('業績一覧PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('professor/achievements')
                            ->visibility('public')
                            ->maxSize(20 * 1024)
                            ->validationMessages(['max' => 'ファイルサイズが大きすぎます（上限 20 MB）。'])
                            ->downloadable()
                            ->openable()
                            ->nullable()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('achievements_pdf_note')
                            ->label('補足テキスト')
                            ->placeholder('例: 2026年4月15日現在')
                            ->helperText('リンクの下に小さく表示される注釈。空欄でもOK。')
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(3),

                Forms\Components\Section::make('ギャラリー写真')
                    ->description('教授・研究室に関する写真を複数枚登録できます。ドラッグで並び替え可能。')
                    ->schema([
                        Forms\Components\FileUpload::make('gallery_photo_urls')
                            ->label('')
                            ->image()
                            ->multiple()
                            ->maxFiles(20)
                            ->disk('public')
                            ->directory('professor/gallery')
                            ->visibility('public')
                            ->imagePreviewHeight('120')
                            ->maxSize(5 * 1024)
                            ->reorderable()
                            ->deletable()
                            ->nullable()
                            ->hintAction(MediaPickerAction::make('gallery_photo_urls', null, true)),
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
                    ->size(48),

                Tables\Columns\TextColumn::make('name')
                    ->label('氏名'),

                Tables\Columns\TextColumn::make('title')
                    ->label('肩書き')
                    ->limit(40),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('更新日')
                    ->date('Y.m.d')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProfessors::route('/'),
            'create' => Pages\CreateProfessor::route('/create'),
            'edit'   => Pages\EditProfessor::route('/{record}/edit'),
        ];
    }
}
