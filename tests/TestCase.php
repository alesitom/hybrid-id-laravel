<?php

declare(strict_types=1);

namespace HybridId\Laravel\Tests;

use HybridId\Laravel\HybridIdServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [HybridIdServiceProvider::class];
    }
}
