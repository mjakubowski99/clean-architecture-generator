<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Commands;

use Devpark\CleanArchitecture\Utils\Directory\Domain;
use Devpark\CleanArchitecture\Utils\Directory\DomainResult;
use Devpark\CleanArchitecture\Utils\Directory\UseCaseResult;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeUseCaseResultCommand extends DomainGeneratorCommand
{
    protected $signature = 'make:use-case-result {name} {domain}';

    protected $description = 'Make use case result';

    private UseCaseResult $use_case_result;
    private DomainResult $domain_result;

    public function __construct(Filesystem $files, Domain $domain, UseCaseResult $use_case_result, DomainResult $domain_result)
    {
        parent::__construct($files, $domain);
        $this->use_case_result = $use_case_result;
        $this->domain_result = $domain_result;
    }

    public function handle(): void
    {
        if (!$this->domain->directoryExists($this->getDomain()) ) {
            $this->error("Fail! Domain {$this->getDomain()} does not exists!");
            return;
        }

        if (!$this->use_case_result->directoryExists($this->getDomain())) {
            $this->makeDirectory($this->use_case_result->directoryBasePath($this->getDomain()));
        }
        if (!$this->domain_result->directoryExists($this->getDomain())) {
            $this->makeDirectory($this->domain_result->directoryBasePath($this->getDomain()));
        }

        $interface = $this->buildFromStub([
            '{{$namespace}}' => $this->use_case_result->directoryNamespace($this->getDomain()),
            '{{$class}}' => $this->getInterface()
        ]);

        $file_base_path = $this->use_case_result->fileBasePath($this->getDomain(), $this->getInterface());
        $file_namespace = $this->use_case_result->fileNamespace($this->getDomain(), $this->getInterface());

        $this->files->put($file_base_path, $interface);
        $this->info("Use case result {$file_namespace} successfully created");

        $class = $this->buildFromStub([
            '{{$namespace}}' => $this->domain_result->directoryNamespace($this->getDomain()),
            '{{$class}}' => $this->getClass(),
            '{{$result_contract_namespace}}' => $file_namespace,
            '{{$result_contract}}' => $this->getInterface()
        ], $this->getImplementationStub());
        $file_base_path = $this->domain_result->fileBasePath($this->getDomain(), $this->getClass());
        $file_namespace = $this->domain_result->fileNamespace($this->getDomain(), $this->getClass());

        $this->files->put($file_base_path, $class);
        $this->info("Use case {$file_namespace} successfully created");
    }

    protected function getStub(): string
    {
        return __DIR__.'/stubs/result_contract.stub';
    }

    protected function getImplementationStub(): string
    {
        return __DIR__.'/stubs/result.stub';
    }
}
