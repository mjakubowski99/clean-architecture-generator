<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils;

class File
{
    public static function fixPath(string $path): string
    {
        return str_replace(['/', '\\', '//', '///'], DIRECTORY_SEPARATOR, $path);
    }
}
