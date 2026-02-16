<?php

declare(strict_types=1);

namespace HybridId\Laravel;

use HybridId\IdGenerator;

/**
 * Eloquent trait for models that use HybridId as their primary key.
 *
 * Automatically generates a HybridId on model creation, configures the
 * key type as string, and disables auto-incrementing.
 *
 * Override $idPrefix on your model to set a Stripe-style prefix:
 *
 *     class Order extends Model {
 *         use HasHybridId;
 *         protected static string $idPrefix = 'ord';
 *     }
 */
trait HasHybridId
{
    public function getKeyType(): string
    {
        return 'string';
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public static function bootHasHybridId(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $generator = app(IdGenerator::class);
                $prefix = static::hybridIdPrefix();
                $model->{$model->getKeyName()} = $generator->generate($prefix);
            }
        });
    }

    /**
     * Get the prefix for this model's HybridId.
     *
     * Override $idPrefix on your model to customize:
     *     protected static string $idPrefix = 'usr';
     */
    protected static function hybridIdPrefix(): ?string
    {
        return property_exists(static::class, 'idPrefix') ? static::$idPrefix : null;
    }
}
