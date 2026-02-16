<?php

declare(strict_types=1);

namespace HybridId\Laravel\Tests;

use HybridId\HybridIdGenerator;
use HybridId\IdGenerator;

final class ServiceProviderTest extends TestCase
{
    public function testBindsIdGeneratorInterface(): void
    {
        $generator = $this->app->make(IdGenerator::class);

        $this->assertInstanceOf(HybridIdGenerator::class, $generator);
    }

    public function testResolvesAsSingleton(): void
    {
        $a = $this->app->make(IdGenerator::class);
        $b = $this->app->make(IdGenerator::class);

        $this->assertSame($a, $b);
    }

    public function testHybridIdGeneratorAlias(): void
    {
        $generator = $this->app->make(HybridIdGenerator::class);

        $this->assertInstanceOf(HybridIdGenerator::class, $generator);
    }

    public function testDefaultConfig(): void
    {
        $config = $this->app['config']['hybrid-id'];

        $this->assertSame('standard', $config['profile']);
        $this->assertNull($config['node']);
        $this->assertFalse($config['require_explicit_node']);
    }

    public function testCustomProfileFromConfig(): void
    {
        $this->app['config']->set('hybrid-id.profile', 'compact');

        // Re-bind to pick up new config
        $this->app->forgetInstance(IdGenerator::class);
        $generator = $this->app->make(IdGenerator::class);

        $this->assertSame('compact', $generator->getProfile());
    }

    public function testCustomNodeFromConfig(): void
    {
        $this->app['config']->set('hybrid-id.node', 'Z9');

        $this->app->forgetInstance(IdGenerator::class);
        $generator = $this->app->make(IdGenerator::class);

        $this->assertSame('Z9', $generator->getNode());
    }

    public function testGeneratesValidIds(): void
    {
        $generator = $this->app->make(IdGenerator::class);
        $id = $generator->generate();

        $this->assertSame(20, strlen($id));
        $this->assertTrue(HybridIdGenerator::isValid($id));
    }

    public function testGeneratesValidPrefixedIds(): void
    {
        $generator = $this->app->make(IdGenerator::class);
        $id = $generator->generate('usr');

        $this->assertStringStartsWith('usr_', $id);
        $this->assertTrue(HybridIdGenerator::isValid($id));
    }
}
