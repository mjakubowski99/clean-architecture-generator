<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Tests;

use Devpark\CleanArchitecture\CleanArchitectureServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [CleanArchitectureServiceProvider::class];
    }
}