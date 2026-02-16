<?php

declare(strict_types=1);

namespace HybridId\Laravel\Tests;

use HybridId\HybridIdGenerator;
use HybridId\Laravel\HasHybridId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class HasHybridIdTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('test_models', function (Blueprint $table) {
            $table->string('id', 29)->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('prefixed_models', function (Blueprint $table) {
            $table->string('id', 29)->primary();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function testAutoGeneratesIdOnCreate(): void
    {
        $model = TestModel::create(['name' => 'test']);

        $this->assertNotEmpty($model->id);
        $this->assertSame(20, strlen($model->id));
        $this->assertTrue(HybridIdGenerator::isValid($model->id));
    }

    public function testDoesNotOverrideExistingId(): void
    {
        $model = TestModel::create(['id' => 'custom_id_value12345', 'name' => 'test']);

        $this->assertSame('custom_id_value12345', $model->id);
    }

    public function testGeneratesWithPrefix(): void
    {
        $model = PrefixedModel::create(['name' => 'test']);

        $this->assertStringStartsWith('ord_', $model->id);
        $this->assertTrue(HybridIdGenerator::isValid($model->id));
    }

    public function testKeyTypeIsString(): void
    {
        $model = new TestModel();

        $this->assertSame('string', $model->getKeyType());
    }

    public function testNotIncrementing(): void
    {
        $model = new TestModel();

        $this->assertFalse($model->getIncrementing());
    }

    public function testMultipleModelsGetUniqueIds(): void
    {
        $ids = [];
        for ($i = 0; $i < 10; $i++) {
            $model = TestModel::create(['name' => "test_{$i}"]);
            $ids[] = $model->id;
        }

        $this->assertCount(10, array_unique($ids));
    }

    public function testModelCanBeFoundById(): void
    {
        $created = PrefixedModel::create(['name' => 'findme']);
        $found = PrefixedModel::find($created->id);

        $this->assertNotNull($found);
        $this->assertSame('findme', $found->name);
    }
}

class TestModel extends Model
{
    use HasHybridId;

    protected $table = 'test_models';
    protected $guarded = [];
}

class PrefixedModel extends Model
{
    use HasHybridId;

    protected static string $idPrefix = 'ord';
    protected $table = 'prefixed_models';
    protected $guarded = [];
}
