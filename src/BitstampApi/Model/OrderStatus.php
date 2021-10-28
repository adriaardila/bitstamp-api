<?php

namespace madmis\BitstampApi\Model;

/**
 * Class OrderBook
 * @package madmis\BitstampApi\Model
 */
class OrderStatus
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var array
     */
    protected $transactions;

    /**
     * @var float
     */
    protected $amount_remaining;

    /**
     * @var int
     */
    protected $client_order_id;
}