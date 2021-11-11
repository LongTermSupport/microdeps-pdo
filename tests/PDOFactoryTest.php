<?php

declare(strict_types=1);

namespace MicroDeps\PDO\Tests;

use MicroDeps\PDO\Config;
use MicroDeps\PDO\DSNFactory;
use MicroDeps\PDO\PDOFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @small
 * @covers \MicroDeps\PDO\Config
 * @covers \MicroDeps\PDO\DSNFactory
 * @covers \MicroDeps\PDO\PDOFactory
 *
 * @internal
 */
final class PDOFactoryTest extends TestCase
{
    /** @test */
    public function itCanSetAttributes(): void
    {
        $expected = [];
        $factory = new PDOFactory(new DSNFactory(new Config()));
        $factory->setAttributes($expected);
        $reflection = new ReflectionClass($factory);
        $property = $reflection->getProperty('attributes');
        $property->setAccessible(true);
        $actual = $property->getValue($factory);
        self::assertSame($expected, $actual);
    }

    /** @test */
    public function itCanCreateAnSqliteConnection(): void
    {
        $filePath = __DIR__ . '/../var/' . __FILE__ . '.sqlite';
        $fileDir = \dirname($filePath);
        if (!is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
        try {
            $config = new Config(
                [
                    Config::KEY_DB_TYPE => Config::DB_TYPE_SQLITE_FILE,
                    Config::KEY_SQLITE_PATH => $filePath,
                ]
            );
            $factory = new PDOFactory(new DSNFactory($config));
            $pdo = $factory->getConnection();
            $pdo->exec('CREATE TABLE foo (id INTEGER PRIMARY KEY, name TEXT NOT NULL)');
            $expected = [['name' => 'foo']];
            $query = $pdo->query("SELECT name FROM sqlite_master WHERE type = 'table'");
            if (false === $query) {
                self::fail('Failed running query');
            }
            $actual = $query->fetchAll();
            self::assertSame($expected, $actual);
        } finally {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
