<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Commands;

use Devpark\CleanArchitecture\Utils\Directory\Domain;
use Devpark\CleanArchitecture\Utils\Directory\UseCase;
use Devpark\CleanArchitecture\Utils\Directory\UseCaseResult;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeUseCaseCommand extends DomainGeneratorCommand
{
    protected $signature = 'make:use-case {name} {domain} {--result=}';

    protected $description = 'Make service';

    private UseCase $use_case;

    private UseCaseResult $use_case_result;

    public function __construct(Filesystem $files, UseCase $use_case, UseCaseResult $use_case_result, Domain $domain)
    {
        parent::__construct($files, $domain);
        $this->use_case = $use_case;
        $this->use_case_result = $use_case_result;
    }

    public function handle(): void
    {
        if (!$this->domain->directoryExists($this->getDomain()) ) {
            $this->error("Fail! Domain {$this->getDomain()} does not exists!");
            return;
        }

        if (!$this->use_case->directoryExists($this->getDomain())) {
            $this->makeDirectory($this->use_case->directoryBasePath($this->getDomain()));
        }

        $this->addUseCasesDirectoryToComposerAutoload();

        if ($this->option('result') && !in_array($this->option('result'), self::DATA_TYPES, true)) {
            $this->makeResultIfNotExists();
        }

        $class = $this->buildFromStub([
            '{{$namespace}}' => $this->use_case->directoryNamespace($this->getDomain()),
            '{{$result_import}}' => $this->getResultImport(),
            '{{$method}}' => Str::camel($this->getClass()),
            '{{$class}}' => $this->getClass(),
            '{{$result}}' => $this->getUseCaseResult()
        ]);

        $file_base_path = $this->use_case->fileBasePath($this->getDomain(), $this->getClass());
        $file_namespace = $this->use_case->fileNamespace($this->getDomain(), $this->getClass());

        $this->files->put($file_base_path, $class);
        $this->info("Use case {$file_namespace} successfully created");
    }

    protected function getStub(): string
    {
        return __DIR__.'/stubs/use_case.stub';
    }

    private function makeResultIfNotExists()
    {
        if (!$this->use_case_result->fileExists($this->getDomain(), $this->option('result'))) {
            $this->call(MakeUseCaseResultCommand::class, [
                'name' => $this->option('result'),
                'domain' => $this->getDomain()
            ]);
        }
    }

    private function getResultImport(): string
    {
        if (!$this->option('result') || in_array($this->option('result'), self::DATA_TYPES, true)) {
            return "";
        }
        $namespace = $this->use_case_result->fileNamespace($this->getDomain(), $this->option('result'));

        return "use {$namespace};";
    }

    private function getUseCaseResult(): string
    {
        return $this->option('result') ? $this->option('result') : 'mixed';
    }
}
