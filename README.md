# HybridId for Laravel

Laravel integration for [HybridId](https://github.com/alesitom/hybridId_package) — compact, time-sortable unique IDs as a drop-in UUID replacement for Eloquent models.

[![Tests](https://img.shields.io/github/actions/workflow/status/alesitom/hybrid-id-laravel/ci.yml?style=flat-square&label=tests)](https://github.com/alesitom/hybrid-id-laravel/actions)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-blue?style=flat-square)](https://phpstan.org/)

## Installation

```bash
composer require alesitom/hybrid-id-laravel
```

The service provider is auto-discovered. No manual registration needed.

## Quick Start

Add the `HasHybridId` trait to any model:

```php
use HybridId\Laravel\HasHybridId;

class User extends Model
{
    use HasHybridId;

    protected static string $idPrefix = 'usr';
}
```

That's it. New models automatically get a HybridId on creation:

```php
$user = User::create(['name' => 'Jane']);
$user->id;  // usr_0VBFDQz4CYRtntu09sbf
```

## Migration

Your primary key column must be a string, not an auto-incrementing integer:

```php
Schema::create('users', function (Blueprint $table) {
    $table->string('id', 29)->collation('ascii_bin')->primary();
    // ... other columns
    $table->timestamps();
});
```

Use `ascii_bin` collation on MySQL/MariaDB to preserve case-sensitive ordering. See [core docs](https://github.com/alesitom/hybridId_package#collation-important-for-mysqlmariadb) for details.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=hybrid-id-config
```

This creates `config/hybrid-id.php`:

```php
return [
    'profile' => env('HYBRID_ID_PROFILE', 'standard'),
    'node' => env('HYBRID_ID_NODE'),
    'require_explicit_node' => (bool) env('HYBRID_ID_REQUIRE_NODE', false),
    'blind' => (bool) env('HYBRID_ID_BLIND', false),
    'blind_secret' => env('HYBRID_ID_BLIND_SECRET'),
];
```

Set `HYBRID_ID_REQUIRE_NODE=1` in production to enforce explicit node assignment.

## Blind Mode

Enable blind mode to HMAC-hash timestamps and node info, making creation time unextractable:

```env
HYBRID_ID_BLIND=true
HYBRID_ID_BLIND_SECRET=base64encodedvalue...
```

Generate a secret: `php -r "echo base64_encode(random_bytes(32)) . PHP_EOL;"`

See [Blind Mode docs](https://github.com/alesitom/hybridId_package/blob/main/docs/blind-mode.md) for details.

## Dependency Injection

The service provider binds `IdGenerator` as a singleton. Inject it anywhere:

```php
use HybridId\IdGenerator;

class OrderService
{
    public function __construct(
        private readonly IdGenerator $idGenerator,
    ) {}

    public function createOrder(): Order
    {
        return Order::create([
            'id' => $this->idGenerator->generate('ord'),
            'status' => 'pending',
        ]);
    }
}
```

## Prefixes

Set `$idPrefix` on your model for Stripe-style self-documenting IDs:

```php
class Order extends Model
{
    use HasHybridId;
    protected static string $idPrefix = 'ord';
}

class Invoice extends Model
{
    use HasHybridId;
    protected static string $idPrefix = 'inv';
}
```

Omit `$idPrefix` for unprefixed IDs.

## How It Works

- `HasHybridId` hooks into Eloquent's `creating` event
- Sets `$keyType = 'string'` and `$incrementing = false` automatically
- If the model's primary key is empty at creation time, generates a HybridId
- If the primary key is already set (e.g., manual assignment), it is not overwritten
- The generator instance is resolved from the container (singleton)

## Requirements

- PHP 8.3, 8.4, or 8.5
- Laravel 11 or 12
- [alesitom/hybrid-id](https://github.com/alesitom/hybridId_package) ^4.1 (installed automatically)

## License

MIT
