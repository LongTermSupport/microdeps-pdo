<?php

declare(strict_types=1);

namespace MicroDeps\PDO;

use MicroDeps\PDO\Exception\ConfigException;
use MicroDeps\PDO\Exception\DSNException;

final class DSNFactory
{
    public function __construct(private Config $config)
    {
    }

    /**
     * @throws DSNException
     *
     * @return array{string, ?string, ?string} an array containing the DSN, user and pass
     */
    public function getDsnUserPass(): array
    {
        try {
            $type = $this->config->getConfig($this->config::KEY_DB_TYPE);

            return match ($type) {
                $this->config::DB_TYPE_MYSQL => $this->getMySQLDSN(),
                $this->config::DB_TYPE_SQLITE_FILE => $this->getSqliteFile(),
                default => throw new DSNException(sprintf(DSNException::UNSUPPORTED_DB_TYPE_MSG, $type))
            };
        } catch (ConfigException $e) {
            throw new DSNException(sprintf(DSNException::CONFIG_EXCEPTION_MSG, $e->getMessage()), 0, $e);
        }
    }

    /**
     * @throws ConfigException
     *
     * @return array{string, ?string, ?string}
     */
    private function getMySQLDSN(): array
    {
        $host = $this->config->getConfig($this->config::KEY_DB_SERVER);
        $db = $this->config->getConfig($this->config::KEY_DB_DATABASE);
        $port = $this->config->getConfig($this->config::KEY_DB_PORT);
        $charset = $this->config->getConfig($this->config::KEY_DB_CHARSET);
        $user = $this->config->getConfig($this->config::KEY_DB_SERVER_USERNAME);
        $pass = $this->config->getConfig($this->config::KEY_DB_SERVER_PASSWORD);

        return [
            "mysql:host={$host};port={$port};dbname={$db};charset={$charset}",
            $user,
            $pass,
        ];
    }

    /**
     * @throws ConfigException
     *
     * @return array{string, ?string, ?string}
     */
    private function getSqliteFile(): array
    {
        $path = $this->config->getConfig($this->config::KEY_SQLITE_PATH);

        return ['sqlite:' . $path, null, null];
    }
}
