<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture;

use Devpark\CleanArchitecture\Commands\MakeDomainCommand;
use Devpark\CleanArchitecture\Commands\MakeUseCaseCommand;
use Devpark\CleanArchitecture\Commands\MakeUseCaseResultCommand;
use Illuminate\Support\ServiceProvider;

class CleanArchitectureServiceProvider extends ServiceProvider
{
    protected array $commands = [
        MakeDomainCommand::class,
        MakeUseCaseCommand::class,
        MakeUseCaseResultCommand::class
    ];

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);

            $this->publishes([
                __DIR__.'/../config/clean_architecture.php' => config_path('clean_architecture'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clean_architecture.php', 'clean_architecture');
    }
}
