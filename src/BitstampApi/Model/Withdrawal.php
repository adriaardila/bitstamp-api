<?php

namespace madmis\BitstampApi\Model;

/**
 * Class OrderBook
 * @package madmis\BitstampApi\Model
 */
class Withdrawal
{
    /**
     * @var int
     */
    protected $id;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}