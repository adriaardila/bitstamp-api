<?php

namespace madmis\BitstampApi\Model;

/**
 * Class OrderBook
 * @package madmis\BitstampApi\Model
 */
class MarketOrder
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $datetime;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @return int
     */
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

    /**
     * @return \DateTime
     */
    public function getDatetime(): \DateTime
    {
        return $this->datetime;
    }

    /**
     * @param \DateTime $datetime
     */
    public function setDatetime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}