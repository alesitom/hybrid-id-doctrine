<?php

declare(strict_types=1);

namespace HybridId\Doctrine\Tests;

use Doctrine\ORM\EntityManagerInterface;
use HybridId\Doctrine\HybridIdGenerator;
use HybridId\HybridIdGenerator as CoreGenerator;
use PHPUnit\Framework\TestCase;

final class HybridIdGeneratorTest extends TestCase
{
    public function testGeneratesValidId(): void
    {
        $generator = new HybridIdGenerator();
        $em = $this->createMock(EntityManagerInterface::class);

        $id = $generator->generateId($em, null);

        $this->assertSame(20, strlen($id));
        $this->assertTrue(CoreGenerator::isValid($id));
    }

    public function testGeneratesWithConstructorPrefix(): void
    {
        $generator = new HybridIdGenerator(prefix: 'usr');
        $em = $this->createMock(EntityManagerInterface::class);

        $id = $generator->generateId($em, null);

        $this->assertStringStartsWith('usr_', $id);
        $this->assertTrue(CoreGenerator::isValid($id));
    }

    public function testGeneratesWithCustomCoreGenerator(): void
    {
        $core = new CoreGenerator(profile: 'standard', node: 'A1');
        $generator = new HybridIdGenerator(generator: $core);
        $em = $this->createMock(EntityManagerInterface::class);

        $id = $generator->generateId($em, null);

        $this->assertSame(20, strlen($id));
        $this->assertSame('A1', CoreGenerator::extractNode($id));
    }

    public function testUsesEntityPrefixMethod(): void
    {
        $generator = new HybridIdGenerator();
        $em = $this->createMock(EntityManagerInterface::class);
        $entity = new class {
            public static function hybridIdPrefix(): string
            {
                return 'ord';
            }
        };

        $id = $generator->generateId($em, $entity);

        $this->assertStringStartsWith('ord_', $id);
    }

    public function testConstructorPrefixOverridesEntityMethod(): void
    {
        $generator = new HybridIdGenerator(prefix: 'inv');
        $em = $this->createMock(EntityManagerInterface::class);
        $entity = new class {
            public static function hybridIdPrefix(): string
            {
                return 'ord';
            }
        };

        $id = $generator->generateId($em, $entity);

        $this->assertStringStartsWith('inv_', $id);
    }

    public function testGeneratesUniqueIds(): void
    {
        $generator = new HybridIdGenerator();
        $em = $this->createMock(EntityManagerInterface::class);

        $ids = [];
        for ($i = 0; $i < 100; $i++) {
            $ids[] = $generator->generateId($em, null);
        }

        $this->assertCount(100, array_unique($ids));
    }

    public function testNullEntityIsHandled(): void
    {
        $generator = new HybridIdGenerator();
        $em = $this->createMock(EntityManagerInterface::class);

        $id = $generator->generateId($em, null);

        $this->assertSame(20, strlen($id));
    }
}
