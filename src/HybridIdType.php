<?php

declare(strict_types=1);

namespace HybridId\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Doctrine DBAL type for HybridId columns.
 *
 * Maps HybridId strings to VARCHAR with correct length based on profile.
 * Use 'hybrid_id' as the column type in your entity mappings.
 */
class HybridIdType extends Type
{
    public const string NAME = 'hybrid_id';

    /**
     * Default column length: accommodates standard profile (20) + max prefix (8) + underscore (1).
     */
    private const int DEFAULT_LENGTH = 29;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] ??= self::DEFAULT_LENGTH;

        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : (string) $value; // @phpstan-ignore cast.string
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : (string) $value; // @phpstan-ignore cast.string
    }
}
