<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Utils\Directory;

interface Directory
{
    public function directoryBasePath(string $domain): string;

    public function directoryRelativePath(string $domain): string;

    public function directoryNamespace(string $domain): string;

    public function directoryExists(string $domain): bool;

    public function fileBasePath(string $domain, string $file): string;

    public function fileRelativePath(string $domain, string $file): string;

    public function fileNamespace(string $domain, string $file): string;

    public function fileExists(string $domain, string $file): bool;
}
