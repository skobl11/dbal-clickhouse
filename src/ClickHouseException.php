<?php

declare(strict_types=1);

/*
 * This file is part of the FODDBALClickHouse package -- Doctrine DBAL library
 * for ClickHouse (a column-oriented DBMS for OLAP <https://clickhouse.yandex/>)
 *
 * (c) FriendsOfDoctrine <https://github.com/FriendsOfDoctrine/>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOD\DBALClickHouse;

use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Query;

/**
 * Specific Exception for ClickHouse
 */
class ClickHouseException extends Exception implements ExceptionConverter
{
    public static function notSupported($method): ClickHouseException
    {
        return new self(sprintf("Operation '%s' is not supported by platform.", $method));
    }

    public function convert(\Doctrine\DBAL\Driver\Exception $exception, ?Query $query): DriverException
    {
        return new DriverException($exception, $query);
    }
}
