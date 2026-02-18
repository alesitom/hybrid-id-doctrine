<?php

declare(strict_types=1);

namespace HybridId\Doctrine\Tests;

use Doctrine\DBAL\Platforms\SQLitePlatform;
use Doctrine\DBAL\Types\Type;
use HybridId\Doctrine\HybridIdType;
use PHPUnit\Framework\TestCase;

final class HybridIdTypeTest extends TestCase
{
    private HybridIdType $type;

    protected function setUp(): void
    {
        if (!Type::hasType(HybridIdType::NAME)) {
            Type::addType(HybridIdType::NAME, HybridIdType::class);
        }

        $this->type = Type::getType(HybridIdType::NAME);
    }

    public function testRegistersAsHybridId(): void
    {
        $this->assertInstanceOf(HybridIdType::class, Type::getType('hybrid_id'));
    }

    public function testSqlDeclarationDefaultLength(): void
    {
        $platform = new SQLitePlatform();
        $sql = $this->type->getSQLDeclaration([], $platform);

        $this->assertStringContainsString('VARCHAR', $sql);
        $this->assertStringContainsString('29', $sql);
    }

    public function testSqlDeclarationCustomLength(): void
    {
        $platform = new SQLitePlatform();
        $sql = $this->type->getSQLDeclaration(['length' => 33], $platform);

        $this->assertStringContainsString('33', $sql);
    }

    public function testConvertToPHPValueReturnsString(): void
    {
        $platform = new SQLitePlatform();

        $this->assertSame('usr_abc123', $this->type->convertToPHPValue('usr_abc123', $platform));
    }

    public function testConvertToPHPValueReturnsNullForNull(): void
    {
        $platform = new SQLitePlatform();

        $this->assertNull($this->type->convertToPHPValue(null, $platform));
    }

    public function testConvertToDatabaseValueReturnsString(): void
    {
        $platform = new SQLitePlatform();

        $this->assertSame('usr_abc123', $this->type->convertToDatabaseValue('usr_abc123', $platform));
    }

    public function testConvertToDatabaseValueReturnsNullForNull(): void
    {
        $platform = new SQLitePlatform();

        $this->assertNull($this->type->convertToDatabaseValue(null, $platform));
    }
}
