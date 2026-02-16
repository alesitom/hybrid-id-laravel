<?php

declare(strict_types=1);

namespace HybridId\Laravel;

use HybridId\HybridIdGenerator;
use HybridId\IdGenerator;
use Illuminate\Support\ServiceProvider;

class HybridIdServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/hybrid-id.php', 'hybrid-id');

        $this->app->singleton(IdGenerator::class, function ($app) {
            $config = $app['config']['hybrid-id'];

            return new HybridIdGenerator(
                profile: $config['profile'] ?? 'standard',
                node: $config['node'] ?? null,
                requireExplicitNode: $config['require_explicit_node'] ?? false,
            );
        });

        $this->app->alias(IdGenerator::class, HybridIdGenerator::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/hybrid-id.php' => config_path('hybrid-id.php'),
            ], 'hybrid-id-config');
        }
    }
}
