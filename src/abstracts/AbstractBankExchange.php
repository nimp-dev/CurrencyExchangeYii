<?php

namespace Nimp\CurrencyExchange\abstracts;

use Nimp\CurrencyExchange\exceptions\BankDataNotFoundException;
use yii\httpclient\Client;
use yii\httpclient\JsonParser;
use stdClass;
use Exception;

abstract class AbstractBankExchange
{
    protected array $supportedCurrencies;
    protected string $apiUrl;

    public function __construct(
        array  $supportedCurrencies,
        string $apiUrl,
    )
    {
        $this->supportedCurrencies = $supportedCurrencies;
        $this->apiUrl = $apiUrl;
    }

    /**
     * @param string $url
     * @param string $method
     * @return array|stdClass
     * @throws BankDataNotFoundException
     */
    protected function getApiRequest(string $url, string $method = 'GET'): array|stdClass
    {
        $client = new Client(['parsers' => [Client::FORMAT_JSON => ['class' => JsonParser::class, 'asArray' => false]]]);

        try {
            $response = $client->createRequest()
                ->setMethod($method)
                ->setUrl($url)
                ->setOptions(['timeout' => 100])
                ->send();

            if (!$response->isOk) {
                throw new BankDataNotFoundException(
                    "API does not respond to URL: " . $url . PHP_EOL .
                    'http error code :' . $response->getStatusCode()
                );
            }

            return $response->getData();
        } catch (Exception $e) {
            throw new BankDataNotFoundException(
                "Error while requesting API by URL: " . $url . PHP_EOL .
                'error_info :' . print_r($e->getMessage())
            );
        }
    }

}