<?php

if (! function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        if (! $path) return null;
        if (str_starts_with($path, 'http')) return $path;
        return asset('storage/' . $path);
    }
}
