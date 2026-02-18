<?php

declare(strict_types=1);

namespace HybridId\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
use HybridId\HybridIdGenerator as CoreGenerator;

/**
 * Doctrine ORM ID generator that produces HybridIds.
 *
 * Usage with PHP attributes:
 *
 *     #[ORM\Id]
 *     #[ORM\Column(type: 'hybrid_id', length: 29)]
 *     #[ORM\GeneratedValue(strategy: 'CUSTOM')]
 *     #[ORM\CustomIdGenerator(class: HybridIdGenerator::class)]
 *     private string $id;
 *
 * By default uses 'standard' profile with auto-detected node and no prefix.
 * For custom configuration, register a pre-configured instance as a service.
 */
class HybridIdGenerator extends AbstractIdGenerator
{
    private CoreGenerator $generator;
    private ?string $prefix;

    public function __construct(
        ?CoreGenerator $generator = null,
        ?string $prefix = null,
    ) {
        $this->generator = $generator ?? new CoreGenerator(requireExplicitNode: false);
        $this->prefix = $prefix;
    }

    public function generateId(EntityManagerInterface $em, object|null $entity): string
    {
        $prefix = $this->prefix;

        // Allow entities to declare their own prefix via a static method
        if ($prefix === null && $entity !== null && method_exists($entity, 'hybridIdPrefix')) {
            /** @var string $prefix */
            $prefix = $entity::hybridIdPrefix();
        }

        return $this->generator->generate($prefix);
    }
}
