<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Tests\Feature\Commands;

use Devpark\CleanArchitecture\Tests\TestCase;
use Devpark\CleanArchitecture\Utils\Composer;
use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class MakeDomainCommandTest extends TestCase
{
    private Repository $config;

    /** @test */
    public function handle_WhenDomainDirectoryDoesNotExists_ShouldCreateNewDomainDirectoryPrefixedByDirectoryFromConfigFileAndAddDomainToAutoloadInComposer(): void
    {
        //Given
        $domain_path = "../";
        $domain = "Auth";
        $autoload = "psr-4";
        $domain_namespace_prefix = null;

        $this->config->set('clean_architecture.domain_path', $domain_path);
        $this->config->set('clean_architecture.domain_namespace_prefix', $domain_namespace_prefix);
        $this->config->set('clean_architecture.autoload', $autoload);

        $domain_real_path = $this->app->basePath($domain_path.$domain);
        File::deleteDirectory($domain_real_path);

        $expected_namespace = "Auth";
        $expected_path = "../Auth";

        //When
        $this->artisan("make:domain {$domain}");
        $namespaces = Arr::get(Composer::readComposerFile(), "autoload.{$autoload}");

        //Then
        $this->assertDirectoryExists($domain_real_path);
        $this->assertArrayHasKey($expected_namespace, $namespaces);
        $this->assertSame($namespaces[$expected_namespace], $expected_path);

        File::deleteDirectory($domain_real_path);
    }

    /** @test */
    public function handle_WhenDomainDirectoryExists_ShouldNotCreateNewDirectory(): void
    {
        //Given
        $domain_path = "../";
        $domain = "Auth";
        $test_file = "test.txt";

        $domain_real_path = $this->app->basePath($domain_path.$domain);
        $file_real_path = $domain_real_path."/".$test_file;

        File::deleteDirectory($domain_real_path);
        File::makeDirectory($domain_real_path);
        file_put_contents($file_real_path, 'test content');

        //When
        $this->artisan("make:domain {$domain}")
            ->expectsOutput("Fail! This domain already exists");

        //Then
        $this->assertFileExists($file_real_path);

        File::deleteDirectory($domain_real_path);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = $this->app->make(Repository::class);
    }
}