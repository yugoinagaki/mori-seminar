<?php

if (! function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        if (! $path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return asset('storage/' . ltrim($path, '/'));
    }
}

if (! function_exists('time_ago')) {
    function time_ago(?string $dateStr): string
    {
        if (! $dateStr) return '';
        $diff = max(0, time() - strtotime($dateStr));
        $h    = (int) floor($diff / 3600);
        $d    = (int) floor($h / 24);
        if ($d > 0) return "{$d}日前";
        if ($h > 0) return "{$h}時間前";
        return 'たった今';
    }
}
