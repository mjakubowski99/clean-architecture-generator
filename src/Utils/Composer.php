<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils;

use Illuminate\Support\Arr;
use Exception;

class Composer
{
    /** @throws Exception */
    public static function addNamespaceToAutoload(string $path, string $namespace): void
    {
        $composer = self::readComposerFile();
        $composer['autoload'][self::getAutoloadStandard()][$namespace] = $path;

        file_put_contents(self::getComposerPath(), json_encode($composer, JSON_PRETTY_PRINT));
    }

    /** @throws Exception */
    public static function getAutoloadStandard(): string
    {
        $autoload = config('clean_architecture.autoload');

        if (!in_array($autoload, ['psr-0', 'psr-4'])) {
            throw new \Exception("Invalid autoload standard defined in config: {$autoload} available standards: psr-0, psr-4");
        }

        return $autoload;
    }

    public static function readComposerFile(): array
    {
        return json_decode(file_get_contents(self::getComposerPath()), true);
    }

    public static function getComposerPath(): string
    {
        return app()->basePath('composer.json');
    }
}
