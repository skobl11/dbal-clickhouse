<?php

namespace FOD\DBALClickHouse;

use Doctrine\DBAL\Driver\FetchUtils;

class Result implements \Doctrine\DBAL\Driver\Result
{

    /** @var list<array<string, mixed>> */
    private $data;

    /** @var int */
    private $columnCount = 0;

    /** @var int */
    private $num = 0;

    /**
     * @param list<array<string, mixed>> $data
     */
    public function __construct(array $data = null)
    {
        $this->data = $data ?? [];
        if (count($data) === 0) {
            return;
        }

        $this->columnCount = count($data[0]);
    }

    /**
     * @return array<string, mixed>|false
     */
    private function fetch()
    {
        if (!isset($this->data[$this->num])) {
            return false;
        }

        return $this->data[$this->num++];
    }

    public function fetchNumeric()
    {
        $row = $this->fetch();

        if ($row === false) {
            return false;
        }

        return array_values($row);
        // TODO: Implement fetchNumeric() method.
    }

    public function fetchAssociative()
    {
        return $this->fetch();
        // TODO: Implement fetchAssociative() method.
    }

    public function fetchOne()
    {
        $row = $this->fetch();

        if ($row === false) {
            return false;
        }

        return reset($row);
    }

    public function fetchAllNumeric(): array
    {
        return FetchUtils::fetchAllNumeric($this);
    }

    public function fetchAllAssociative(): array
    {
        return FetchUtils::fetchAllAssociative($this);
    }

    public function fetchFirstColumn(): array
    {
        return FetchUtils::fetchFirstColumn($this);
    }

    public function rowCount(): int
    {
        return count($this->data);
    }

    public function columnCount(): int
    {
        return $this->columnCount;
    }

    public function free(): void
    {
        $this->data = [];
    }
}