<?php

namespace App\Models;

use App\Observers\AnnualThemeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(AnnualThemeObserver::class)]

class AnnualTheme extends Model
{
    public const SEMESTER_LABELS = [
        'spring' => '春学期',
        'fall'   => '秋学期',
    ];

    protected $fillable = ['year', 'semester', 'title', 'content'];

    public function themeYear()
    {
        return $this->belongsTo(ThemeYear::class, 'year', 'year');
    }

    public function semesterLabel(): ?string
    {
        return $this->semester ? self::SEMESTER_LABELS[$this->semester] : null;
    }

    // Latest semester first within a year: fall → spring → null(通年)
    public function scopeOrderedByRecency(Builder $query): Builder
    {
        return $query->orderByRaw("CASE WHEN semester = 'fall' THEN 0 WHEN semester = 'spring' THEN 1 ELSE 2 END");
    }
}
