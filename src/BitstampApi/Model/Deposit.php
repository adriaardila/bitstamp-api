<?php

namespace madmis\BitstampApi\Model;

/**
 * Class OrderBook
 * @package madmis\BitstampApi\Model
 */
class Deposit
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

}