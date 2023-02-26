<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils\Directory;

use Devpark\CleanArchitecture\Utils\File;

class Domain extends DomainBase implements Directory
{
    public function directoryBasePath(string $domain): string
    {
        return $this->getDomainBasePath($domain);
    }

    public function directoryRelativePath(string $domain): string
    {
        return $this->getDomainPath($domain);
    }

    public function directoryNamespace(string $domain): string
    {
       return $this->getDomainNamespace($domain);
    }

    public function directoryExists(string $domain): bool
    {
        return is_dir($this->getDomainBasePath($domain));
    }

    public function fileBasePath(string $domain, string $file): string
    {
        $path = $this->getDomainBasePath($domain)."/".$file.".php";

        return File::fixPath($path);
    }

    public function fileRelativePath(string $domain, string $file): string
    {
        $path = $this->getDomainPath($domain)."/".$file.".php";
        return File::fixPath($path);
    }

    public function fileNamespace(string $domain, string $file): string
    {
        return $this->getDomainNamespace($domain)."\\".$file;
    }

    public function fileExists(string $domain, string $file): bool
    {
        return file_exists($this->fileBasePath($domain,$file));
    }
}
