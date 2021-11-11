<?php

declare(strict_types=1);

namespace MicroDeps\PDO\Exception;

final class ConfigException extends AbstractException
{
    public const NON_SCALAR_MSG = 'Trying to cast non scalar value to string';
    public const FAILED_FINDING_MSG = 'Failed finding constant/env param %s, this needs to be defined';
}
