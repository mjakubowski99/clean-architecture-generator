<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Commands;

use Devpark\CleanArchitecture\Utils\Composer;
use Devpark\CleanArchitecture\Utils\Directory\Domain;
use Illuminate\Filesystem\Filesystem;

class MakeDomainCommand extends DomainGeneratorCommand
{
    protected $signature = 'make:domain {domain}';

    protected $description = 'Make domain';

    public function handle(): void
    {
        if ($this->domain->directoryExists($this->getDomain())) {
            $this->error("Fail! This domain already exists");
            return;
        }

        $path = $this->domain->directoryRelativePath($this->getDomain());

        $this->makeDirectory($this->domain->directoryBasePath($this->getDomain()));

        $this->info("Directory for domain: {$this->getDomain()} created in: {$path}");

        try {
            Composer::addNamespaceToAutoload($path, $this->domain->directoryNamespace($this->getDomain()));
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    protected function getStub(): string
    {
        throw new \Exception("Not implemented");
    }
}
