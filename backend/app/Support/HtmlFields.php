<?php

namespace App\Support;

class HtmlFields
{
    private const ALLOWED = [
        'annual_theme' => [
            'year'    => null,
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

    public static function isAllowed(string $modelType, string $field): bool
    {
        return array_key_exists($field, self::ALLOWED[$modelType] ?? []);
    }

    public static function profile(string $modelType, string $field): ?string
    {
        return self::ALLOWED[$modelType][$field] ?? null;
    }

    public static function sanitize(string $modelType, string $field, string $value): string
    {
        $profile = self::profile($modelType, $field);

        return $profile === null
            ? strip_tags($value)
            : clean($value, $profile);
    }
}
