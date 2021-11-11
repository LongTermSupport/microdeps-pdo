<?php

declare(strict_types=1);

namespace MicroDeps\PDO\Tests;

use MicroDeps\PDO\Config;
use MicroDeps\PDO\Exception\ConfigException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MicroDeps\PDO\Config
 *
 * @small
 */
final class ConfigTest extends TestCase
{
    /** @test */
    public function itCanLoadConfigFromEnv(): void
    {
        $expected = 'foo';
        $actual = (new Config([Config::KEY_DB_TYPE => $expected]))->getConfig(Config::KEY_DB_TYPE);
        self::assertSame($expected, $actual);
    }

    /** @test */
    public function itWillNormaliseToString(): void
    {
        $value = 1;
        $expected = (string)$value;
        $actual = (new Config([Config::KEY_DB_TYPE => $value]))->getConfig(Config::KEY_DB_TYPE);
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function itCanLoadConfigFromGlobalConstants(): void
    {
        $expected = 'foo';
        \define(Config::KEY_DB_PORT, $expected);
        $actual = (new Config([]))->getConfig(Config::KEY_DB_PORT);
        self::assertSame($expected, $actual);
    }

    /** @test */
    public function itWillLoadDefaultValues(): void
    {
        $expected = Config::DEFAULT_DB_SERVER;
        $actual = (new Config([]))->getConfig(Config::KEY_DB_SERVER);
        self::assertSame($expected, $actual);
    }

    /** @test */
    public function itWillThrowIfNonScalar(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage(ConfigException::NON_SCALAR_MSG);
        /* @phpstan-ignore-next-line */
        (new Config([Config::KEY_DB_TYPE => []]))->getConfig(Config::KEY_DB_TYPE);
    }

    /** @test */
    public function itWillThrowIfNotDefined(): void
    {
        $key = Config::KEY_DB_DATABASE;
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage(sprintf(ConfigException::FAILED_FINDING_MSG, $key));
        (new Config([]))->getConfig($key);
    }
}
