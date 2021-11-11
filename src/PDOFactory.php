<?php

declare(strict_types=1);

namespace MicroDeps\PDO;

use PDO;

final class PDOFactory
{
    /** @var array<int,int> */
    public const ATTRIBUTES_DEFAULT = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    private static PDO $pdo;

    /** @var array<int,int> */
    private array $attributes = self::ATTRIBUTES_DEFAULT;

    public function __construct(private DSNFactory $DSNFactory)
    {
    }

    /**
     * @throws Exception\DSNException
     */
    public function getConnection(): PDO
    {
        return self::$pdo ??= $this->createConnection();
    }

    /**
     * @throws Exception\DSNException
     */
    private function createConnection(): PDO
    {
        [$dsn, $user, $pass] = $this->DSNFactory->getDsnUserPass();

        return new PDO($dsn, $user, $pass, $this->attributes);
    }

    /** @param array<int,int> $attributes */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
