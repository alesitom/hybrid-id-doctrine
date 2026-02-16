# HybridId for Doctrine

Doctrine integration for [HybridId](https://github.com/alesitom/hybridId_package) — DBAL type and ORM ID generator for compact, time-sortable unique IDs.

## Installation

```bash
composer require alesitom/hybrid-id-doctrine
```

## Setup

Register the DBAL type in your application bootstrap:

```php
use Doctrine\DBAL\Types\Type;
use HybridId\Doctrine\HybridIdType;

Type::addType(HybridIdType::NAME, HybridIdType::class);
```

For Symfony, add to `config/packages/doctrine.yaml`:

```yaml
doctrine:
    dbal:
        types:
            hybrid_id: HybridId\Doctrine\HybridIdType
```

## Entity Mapping

```php
use Doctrine\ORM\Mapping as ORM;
use HybridId\Doctrine\HybridIdGenerator;

#[ORM\Entity]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'hybrid_id', length: 29)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: HybridIdGenerator::class)]
    private string $id;

    // ...

    public function getId(): string
    {
        return $this->id;
    }
}
```

## Prefixes

Add a static `hybridIdPrefix()` method to your entity for Stripe-style prefixed IDs:

```php
#[ORM\Entity]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'hybrid_id', length: 29)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: HybridIdGenerator::class)]
    private string $id;

    public static function hybridIdPrefix(): string
    {
        return 'ord';
    }
}
```

## Custom Generator Configuration

For custom profiles or explicit node assignment, register a pre-configured generator as a service:

```php
use HybridId\Doctrine\HybridIdGenerator;
use HybridId\HybridIdGenerator as CoreGenerator;

$core = new CoreGenerator(profile: 'extended', node: 'A1', requireExplicitNode: true);
$generator = new HybridIdGenerator(generator: $core, prefix: 'usr');
```

In Symfony, register as a service:

```yaml
services:
    HybridId\Doctrine\HybridIdGenerator:
        arguments:
            $generator: '@HybridId\HybridIdGenerator'
            $prefix: null

    HybridId\HybridIdGenerator:
        arguments:
            $profile: '%env(HYBRID_ID_PROFILE)%'
            $node: '%env(HYBRID_ID_NODE)%'
```

## Column Sizing

The default column length is 29 (standard profile + max 8-char prefix + underscore). Adjust based on your profile:

| Profile | No prefix | With prefix (max 8) |
|---|---|---|
| `compact` | `length: 16` | `length: 25` |
| `standard` | `length: 20` | `length: 29` |
| `extended` | `length: 24` | `length: 33` |

Use `ascii_bin` collation on MySQL/MariaDB. See [core docs](https://github.com/alesitom/hybridId_package#collation-important-for-mysqlmariadb).

## Components

### HybridIdType

DBAL type that maps `hybrid_id` columns to PHP strings. Handles NULL values transparently.

### HybridIdGenerator

ORM ID generator implementing `AbstractIdGenerator`. Produces HybridIds on entity persist. Supports:
- Default configuration (standard profile, auto-detected node)
- Custom core generator via constructor injection
- Per-entity prefixes via static `hybridIdPrefix()` method
- Global prefix via constructor parameter

## Requirements

- PHP 8.3, 8.4, or 8.5
- Doctrine DBAL ^4.0
- Doctrine ORM ^3.0
- [alesitom/hybrid-id](https://github.com/alesitom/hybridId_package) ^3.2 (installed automatically)

## License

MIT
