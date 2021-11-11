<?php

declare(strict_types=1);

namespace MicroDeps\PDO;

use MicroDeps\PDO\Exception\ConfigException;

final class Config
{
    public const KEY_DB_SERVER_USERNAME = 'DB_SERVER_USERNAME';
    public const KEY_DB_SERVER_PASSWORD = 'DB_SERVER_PASSWORD';
    public const KEY_DB_DATABASE = 'DB_DATABASE';

    public const KEY_SQLITE_PATH = 'DB_SQLITE_PATH';

    public const KEY_DB_TYPE = 'DB_TYPE';
    public const DB_TYPE_MYSQL = 'mysql';
    public const DB_TYPE_SQLITE_FILE = 'sqlite';
    public const DEFAULT_DB_TYPE = self::DB_TYPE_MYSQL;

    public const KEY_DB_CHARSET = 'DB_CHARSET';
    public const DEFAULT_DB_CHARSET = 'utf8mb4';

    public const KEY_DB_SERVER = 'DB_SERVER';
    public const DEFAULT_DB_SERVER = 'localhost';

    public const DEFAULT_DB_PORT = '3306';
    public const KEY_DB_PORT = 'DB_PORT';

    public const KEYS_WITH_DEFAULTS = [
        self::KEY_DB_TYPE => self::DEFAULT_DB_TYPE,
        self::KEY_DB_CHARSET => self::DEFAULT_DB_CHARSET,
        self::KEY_DB_SERVER => self::DEFAULT_DB_SERVER,
        self::KEY_DB_PORT => self::DEFAULT_DB_PORT,
    ];

    /** @var array<string,string> */
    private array $env;

    /** @param array<string,int|string|float|bool> $env */
    public function __construct(array $env = null)
    {
        $this->env = $env ?? $_ENV;
    }

    /**
     * @throws ConfigException
     */
    public function getConfig(string $key): string
    {
        if (isset($this->env[$key])) {
            return $this->normaliseToString($this->env[$key]);
        }
        if (\defined($key)) {
            return $this->normaliseToString(\constant($key));
        }
        if (isset(self::KEYS_WITH_DEFAULTS[$key])) {
            return $this->normaliseToString(self::KEYS_WITH_DEFAULTS[$key]);
        }
        throw new ConfigException(sprintf(ConfigException::FAILED_FINDING_MSG, $key));
    }

    /**
     * @throws ConfigException
     */
    private function normaliseToString(mixed $value): string
    {
        if (is_scalar($value)) {
            return (string)$value;
        }
        throw new ConfigException(ConfigException::NON_SCALAR_MSG);
    }
}
