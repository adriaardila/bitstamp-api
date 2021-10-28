<?php

namespace madmis\BitstampApi\Endpoint;

use Doctrine\Common\Collections\ArrayCollection;
use madmis\BitstampApi\Api;
use madmis\BitstampApi\Model\MarketOrder;
use madmis\BitstampApi\Model\OrderBook;
use madmis\BitstampApi\Model\OrderBookCollection;
use madmis\BitstampApi\Model\Ticker;
use madmis\BitstampApi\Model\Transaction;
use madmis\BitstampApi\Model\Withdrawal;
use madmis\ExchangeApi\Endpoint\AbstractEndpoint;
use madmis\ExchangeApi\Endpoint\EndpointInterface;
use madmis\ExchangeApi\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PublicEndpoint
 * @package madmis\BitstampApi\Endpoint
 */
class PrivateEndpoint extends AbstractEndpoint implements EndpointInterface
{
    private $apiKey;
    private $apiSecret;

    /**
     * @param string $pair
     * @param bool $mapping
     * @return array|Ticker
     * @throws ClientException
     */
    public function buyMarketOrder(string $pair, bool $mapping = false, array $options = [])
    {
        $apiUrn = $this->getApiUrn(['buy/market', $pair]);
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
     * @param string $pair
     * @param bool $mapping
     * @return array|Ticker
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

    private function getSignature(array $options = [], string $apiUrn): array
    {
        $payload = "";
        $i = 0;
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
        $content_type = 'application/x-www-form-urlencoded';
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

        return [
            'headers' => [
                'X-Auth' => "BITSTAMP $this->apiKey",
                'X-Auth-Signature' => hash_hmac("sha256", $message, $this->apiSecret),
                'X-Auth-Nonce' => $nonce,
                'X-Auth-Timestamp' => $timestamp,
                'X-Auth-Version' => 'v2'
            ],
            'form_params' => $options['form_params']
        ];
    }

    public function setApiKeyAndSecret(string $apiKey, string $apiSecret)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }
}
