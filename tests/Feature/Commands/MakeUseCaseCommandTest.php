<?php

declare(strict_types=1);

namespace Devpark\CleanArchitecture\Tests\Feature\Commands;

use Devpark\CleanArchitecture\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class MakeUseCaseCommandTest extends TestCase
{
    public function handle_WhenUseCaseDoesNotExist_ShouldCreateUseCaseAndPlaceItInCorrespondingUseCaseDomainDirectory(): void
    {
        //Given
        $use_case_directory = "../UseCases";
        $use_case = "Login";
        $domain = "Auth";
        Config::set('clean_architecture.domain_path', "../");
        $expected_use_case_path = $this->app->basePath($use_case_directory."/".$domain."/".$use_case.".php");

        //When
        $this->artisan("make:use-case {$use_case} {$domain}");

        //Then
        $this->assertFileExists($expected_use_case_path);
    }
}
