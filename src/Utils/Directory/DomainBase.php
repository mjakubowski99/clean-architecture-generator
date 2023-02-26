<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils\Directory;

use Devpark\CleanArchitecture\Utils\File;

abstract class DomainBase
{
    public function getDomainNamespace(string $domain): string
    {
        $namespace_prefix = config('clean_architecture.domain_namespace_prefix');

        return $namespace_prefix === null ? $domain : $namespace_prefix."\\".$domain;
    }

    public function getDomainPath(string $domain): string
    {
        $path = config('clean_architecture.domain_path') !== null ?
            config('clean_architecture.domain_path').'/'.$domain : $domain;

        return File::fixPath($path);
    }

    public function getDomainBasePath(string $domain): string
    {
        $path = app()->basePath().'/'.$this->getDomainPath($domain);
        return File::fixPath($path);
    }
}
