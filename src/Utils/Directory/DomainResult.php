<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils\Directory;

use Devpark\CleanArchitecture\Utils\File;

class DomainResult extends DomainBase implements Directory
{
    const DIRECTORY = "/Entities/DTO";
    const NAMESPACE_PREFIX = "\\Entities\\DTO";

    public function directoryBasePath(string $domain): string
    {
        return File::fixPath($this->getDomainBasePath($domain).self::DIRECTORY);
    }

    public function directoryRelativePath(string $domain): string
    {
        return File::fixPath($this->getDomainPath($domain).self::DIRECTORY);
    }

    public function directoryNamespace(string $domain): string
    {
        return $this->getDomainNamespace($domain).self::NAMESPACE_PREFIX;
    }

    public function directoryExists(string $domain): bool
    {
        return is_dir($this->directoryBasePath($domain));
    }

    public function fileBasePath(string $domain, string $file): string
    {
        $path = $this->directoryBasePath($domain);

        return File::fixPath($path."/".$file.".php");
    }

    public function fileRelativePath(string $domain, string $file): string
    {
        $path = $this->directoryRelativePath($domain);

        return File::fixPath($path."/".$file.".php");
    }

    public function fileNamespace(string $domain, string $file): string
    {
        return $this->directoryNamespace($domain)."\\".$file;
    }

    public function fileExists(string $domain, string $file): bool
    {
        return file_exists($this->fileBasePath($domain,$file));
    }
}
