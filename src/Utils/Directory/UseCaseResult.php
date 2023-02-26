<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils\Directory;

use Devpark\CleanArchitecture\Utils\File;

class UseCaseResult extends DomainBase implements Directory
{
    public function directoryBasePath(string $domain): string
    {
        $path = $this->getDomainBasePath("UseCases")."/Contracts/".$domain;
        return File::fixPath($path);
    }

    public function directoryRelativePath(string $domain): string
    {
        $path = $this->getDomainPath("UseCases")."/Contracts/".$domain;
        return File::fixPath($path);
    }

    public function directoryNamespace(string $domain): string
    {
        return $this->getDomainNamespace("UseCases")."\\Contracts\\".$domain;
    }

    public function directoryExists(string $domain): bool
    {
        return is_dir($this->directoryBasePath($domain));
    }

    public function fileBasePath(string $domain, string $file): string
    {
        $path = $this->directoryBasePath($domain)."/".$file.".php";
        return File::fixPath($path);
    }

    public function fileRelativePath(string $domain, string $file): string
    {
        $path = $this->directoryRelativePath($domain)."/".$file.".php";
        return File::fixPath($path);
    }

    public function fileNamespace(string $domain, string $file): string
    {
        return $this->directoryNamespace($domain)."\\".$file;
    }

    public function fileExists(string $domain, string $file): bool
    {
        return file_exists($this->fileBasePath($domain, $file));
    }
}