<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Commands;

use Devpark\CleanArchitecture\Utils\Composer;
use Devpark\CleanArchitecture\Utils\Directory\Domain;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

abstract class DomainGeneratorCommand extends GeneratorCommand
{
    const DATA_TYPES = [
        'array',
        'callable',
        'self',
        'static',
        'int',
        'string',
        'bool',
        'object'
    ];

    protected Domain $domain;

    public function __construct(Filesystem $files, Domain $domain)
    {
        parent::__construct($files);
        $this->domain = $domain;
    }

    protected function getDomain(): string
    {
        return $this->argument('domain');
    }

    public function getClass(): string
    {
        return $this->argument('name');
    }

    public function getInterface(): string
    {
        if (!Str::startsWith($this->argument('name'), "I")) {
            return "I".$this->argument('name');
        }

        return $this->argument('name');
    }

    protected function makeDirectory($path): void
    {
        $this->files->makeDirectory($path, 0777, true, true);
    }

    protected function buildFromStub(array $replace_array, ?string $stub=null): string
    {
        $stub = $this->files->get($stub??$this->getStub());

        foreach ($replace_array as $key => $replacement) {
            $stub = str_replace($key, $replacement, $stub);
        }

        return $stub;
    }

    protected function addUseCasesDirectoryToComposerAutoload(): void
    {
        try {
            Composer::addNamespaceToAutoload($this->domain->getDomainPath("UseCases"), $this->domain->directoryNamespace("UseCases"));
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
