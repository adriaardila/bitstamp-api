<?php

namespace madmis\BitstampApi\Endpoint;

use madmis\BitstampApi\Api;
use madmis\BitstampApi\Model\CryptoTransaction;
use madmis\BitstampApi\Model\MarketOrder;
use madmis\BitstampApi\Model\OrderStatus;
use madmis\BitstampApi\Model\Withdrawal;
use madmis\ExchangeApi\Endpoint\AbstractEndpoint;
use madmis\ExchangeApi\Endpoint\EndpointInterface;
use madmis\ExchangeApi\Exception\ClientException;

/**
 * Class PrivateEndpoint
 * @package madmis\BitstampApi\Endpoint
 */
class PrivateEndpoint extends AbstractEndpoint implements EndpointInterface
{
    private $apiKey;
    private $apiSecret;

    /**
     * @param string $pair
     * @param bool $mapping
     * @return array|MarketOrder
     * @throws ClientException
     */
    public function buyMarketOrder(string $pair, bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn(['buy/instant', $pair]);
        $options = $this->getSignature($options, $apiUrn . '/');
        $response = $this->sendRequest(
            Api::POST,
            $apiUrn,
            $options
        );

        if ($mapping && $response) {
            $response = $this->deserializeItem(
                $response,
                MarketOrder::class
            );

        }

        return $response;
    }

    /**
     * @param string $acronym
     * @param bool $mapping
     * @return array|Withdrawal
     * @throws ClientException
     */
    public function withdraw(string $acronym, bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn([$acronym . '_withdrawal']);
        $options = $this->getSignature($options, $apiUrn . '/');
        $response = $this->sendRequest(
            Api::POST,
            $apiUrn,
            $options
        );

        if ($mapping && $response) {
            $response = $this->deserializeItem(
                $response,
                Withdrawal::class
            );

        }

        return $response;
    }

    /**
     * @param string $acronym
     * @param bool $mapping
     * @return array|Deposit
     * @throws ClientException
     */
    public function deposit(string $acronym, bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn([$acronym . '_address']);
        $options = $this->getSignature($options, $apiUrn . '/');
        $response = $this->sendRequest(
            Api::POST,
            $apiUrn,
            $options
        );

        if ($mapping && $response) {
            $response = $this->deserializeItem(
                $response,
                Deposit::class
            );

        }

        return $response;
    }

    /**
     * @param bool $mapping
     * @return array|OrderStatus
     * @throws ClientException
     */
    public function orderStatus(bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn(['order_status']);
        $options = $this->getSignature($options, $apiUrn . '/');
        $response = $this->sendRequest(
            Api::POST,
            $apiUrn,
            $options
        );

        if ($mapping && $response) {
            $response = $this->deserializeItem(
                $response,
                OrderStatus::class
            );

        }

        return $response;
    }

    /**
     * @param bool $mapping
     * @return array|OrderStatus
     * @throws ClientException
     */
    public function cryptoTransactions(bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn(['crypto-transactions']);
        $options = $this->getSignature($options, $apiUrn . '/');
        $response = $this->sendRequest(
            Api::POST,
            $apiUrn,
            $options
        );

        if ($mapping && $response) {
            $response = $this->deserializeItem(
                $response,
                CryptoTransaction::class
            );

        }

        return $response;
    }

    /**
     * @param string $method Http::GET|POST
     * @param string $uri
     * @param array $options Request options to apply to the given
     *                                  request and to the transfer.
     * @return array response
     * @throws ClientException
     */
    protected function sendRequest(string $method, string $uri, array $options = []): array
    {
        $request = $this->client->createRequest($method, $uri . '/');

        return $this->processResponse(
            $this->client->send($request, $options)
        );
    }

    /**
     * @param array $options
     * @param string $apiUrn
     * @return array
     */
    private function getSignature(array $options = [], string $apiUrn): array
    {
        $payload = "";
        $i = 0;
        if (!isset($options['form_params'])) {
            $options['form_params'] = [];
        }
        foreach ($options['form_params'] as $key => $param) {
            if ($i === 0) {
                $payload .= $key . "=" . $param;
            } else {
                $payload .= "&" . $key . "=" . $param;
            }
            $i++;
        }
        $bytes = random_bytes(18);
        $nonce = bin2hex($bytes);
        $timestamp = round(microtime(true) * 1000);
        if(!empty($options['form_params'])) {
            $content_type = 'application/x-www-form-urlencoded';
        } else {
            $content_type = '';
        }
        $query = '';

        $message = 'BITSTAMP ' . $this->apiKey .
            'POST' .
            'www.bitstamp.net' .
            $apiUrn .
            $query .
            $content_type .
            $nonce .
            $timestamp .
            'v2' .
            $payload;

        $data = [
            'headers' => [
                'X-Auth' => "BITSTAMP $this->apiKey",
                'X-Auth-Signature' => hash_hmac("sha256", $message, $this->apiSecret),
                'X-Auth-Nonce' => $nonce,
                'X-Auth-Timestamp' => $timestamp,
                'X-Auth-Version' => 'v2'
            ]
        ];

        if(!empty($options['form_params'])) {
            $data = array_merge($data, $options);
        }

        return $data;
    }

    /**
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function setApiKeyAndSecret(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }
}
