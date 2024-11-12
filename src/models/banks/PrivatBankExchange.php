<?php

namespace Nimp\CurrencyExchange\models\banks;

use DateTime;
use Nimp\CurrencyExchange\abstracts\AbstractBankExchange;
use Nimp\CurrencyExchange\exceptions\BankDataNotFoundException;
use Nimp\CurrencyExchange\interfaces\IBankExchange;

class PrivatBankExchange extends AbstractBankExchange implements IBankExchange
{

    /**
     * @inheritdoc
     */
    public function getSupportedCurrencies(): array
    {
        return $this->supportedCurrencies;
    }

    /**
     * @param DateTime $date
     * @return array
     * @throws BankDataNotFoundException
     */
    public function getExchangeRates(DateTime $date): array
    {
        $url = $this->apiUrl . $date->format('d.m.Y');
        $data = $this->getApiRequest($url);
        $rates = [];

        foreach ($data->exchangeRate as $item) {
            if (isset($item->currency) && in_array($item->currency, $this->getSupportedCurrencies())) {
                $saleRate = $item->saleRate ?? $item->saleRateNB;
                $rates[$item->currency] = $saleRate;
            }
        }

        return $rates;
    }
}