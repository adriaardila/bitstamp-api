<?php

namespace madmis\BitstampApi\Model;

/**
 * Class OrderBook
 * @package madmis\BitstampApi\Model
 */
class CryptoTransaction
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $destinationAddress;

    /**
     * @var string
     */
    protected $txid;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var \DateTime
     */
    protected $datetime;

}