<?php

namespace App\Support;

/**
 * インライン編集で変更を許可するフィールドの定義。
 *
 * ここにないフィールド（status, author_id, role 等）は DraftController で
 * バリデーションエラーになるため、DB に書き込まれない。
 *
 * HTML フィールド（{!! !!} レンダリング）は purifier_profile を指定。
 * null = プレーンテキスト（strip_tags のみ）。
 */
class HtmlFields
{
    // model_type => [field => purifier_profile|null]
    private const ALLOWED = [
        'annual_theme' => [
            'title'   => null,
            'content' => 'default',
        ],
        'professor' => [
            'name'    => null,
            'name_en' => null,
            'title'   => null,
            'bio'     => 'basic',
        ],
        'post' => [
            'title'   => null,
            'excerpt' => null,
            'content' => 'default',
        ],
        'case_study' => [
            'title'       => null,
            'description' => 'basic',
            'content'     => 'default',
        ],
        'member' => [
            'name'      => null,
            'name_kana' => null,
            'major'     => null,
            'bio'       => 'basic',
        ],
        'faq' => [
            'question' => null,
            'answer'   => null,
        ],
        'site_setting' => [
            'hero_image_url' => null,
        ],
    ];

    /** フィールドが編集許可リストに含まれるか */
    public static function isAllowed(string $modelType, string $field): bool
    {
        return array_key_exists($field, self::ALLOWED[$modelType] ?? []);
    }

    /** HTML フィールドの purifier プロファイルを返す（null = プレーンテキスト） */
    public static function profile(string $modelType, string $field): ?string
    {
        return self::ALLOWED[$modelType][$field] ?? null;
    }

    /** 値をサニタイズして返す（許可されていないフィールドは呼び出し前に弾くこと） */
    public static function sanitize(string $modelType, string $field, string $value): string
    {
        $profile = self::profile($modelType, $field);

        return $profile === null
            ? strip_tags($value)
            : clean($value, $profile);
    }
}
