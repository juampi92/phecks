<?php

namespace Juampi92\Phecks\Support;

use Illuminate\Support\Str;

class PathNormalizer
{
    public static function toAbsolute(string $path): string
    {
        if (Str::startsWith('./', $path)) {
            return base_path(
                Str::after('.', $path),
            );
        }

        return $path;
    }

    public static function toRelative(string $path): string
    {
        $path = Str::of($path);

        if (!$path->startsWith('./')) {
            $path = $path->replaceFirst(base_path(), '/')
                ->start('/')
                ->start('.');
        }

        return (string) $path;
    }
}
