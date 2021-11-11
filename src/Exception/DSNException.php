<?php

declare(strict_types=1);

namespace MicroDeps\PDO\Exception;

final class DSNException extends AbstractException
{
    public const UNSUPPORTED_DB_TYPE_MSG = 'Unsupported DB Type: %s';
    public const CONFIG_EXCEPTION_MSG = 'Config Exception when trying to get DSN: %s';
}
