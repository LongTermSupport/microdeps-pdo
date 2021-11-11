<?php

declare(strict_types=1);

namespace MicroDeps\PDO\Tests;

use Generator;
use MicroDeps\PDO\Config;
use MicroDeps\PDO\DSNFactory;
use MicroDeps\PDO\Exception\ConfigException;
use MicroDeps\PDO\Exception\DSNException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \MicroDeps\PDO\DSNFactory
 * @small
 */
final class DSNFactoryTest extends TestCase
{
    private const CONFIG_BASIC = [
        Config::KEY_DB_DATABASE => 'dbname',
        Config::KEY_DB_SERVER_USERNAME => 'username',
        Config::KEY_DB_SERVER_PASSWORD => 'password',
    ];

    /** @return Generator<string, array<int, array<string, string>|string>> */
    public function provideMySQLConfigs(): Generator
    {
        yield 'basic' => [
            self::CONFIG_BASIC,
            'mysql:host=localhost;port=3306;dbname=dbname;charset=utf8mb4',
        ];
        yield 'custom port' => [
            self::CONFIG_BASIC
            + [
                Config::KEY_DB_PORT => '1',
            ],
            'mysql:host=localhost;port=1;dbname=dbname;charset=utf8mb4',
        ];
        yield 'custom charset' => [
            self::CONFIG_BASIC
            + [
                Config::KEY_DB_CHARSET => 'foo',
            ],
            'mysql:host=localhost;port=3306;dbname=dbname;charset=foo',
        ];
    }

    /**
     * @test
     * @dataProvider provideMySQLConfigs
     *
     * @param array<string,string> $env
     */
    public function itCanCreateMySQLDSN(array $env, string $expected): void
    {
        $config = new Config($env);
        try {
            [$actual, ,] = (new DSNFactory($config))->getDsnUserPass();
            self::assertSame($expected, $actual);
        } catch (DSNException $e) {
            self::fail($e->getMessage());
        }
    }

    /** @test */
    public function itThrowsOnUnsupportedType(): void
    {
        $type = 'foo';
        $config = new Config(
            self::CONFIG_BASIC
            + [
                Config::KEY_DB_TYPE => $type,
            ]
        );
        $this->expectException(DSNException::class);
        $this->expectExceptionMessage(sprintf(DSNException::UNSUPPORTED_DB_TYPE_MSG, $type));
        (new DSNFactory($config))->getDsnUserPass();
    }

    /** @test */
    public function itThrowsOnConfigException(): void
    {
        $config = new Config([]);
        $this->expectException(DSNException::class);
        $this->expectExceptionMessage(
            sprintf(
                DSNException::CONFIG_EXCEPTION_MSG,
                sprintf(ConfigException::FAILED_FINDING_MSG, Config::KEY_DB_DATABASE)
            )
        );
        $this->expectExceptionCode(0);
        (new DSNFactory($config))->getDsnUserPass();
    }
}
