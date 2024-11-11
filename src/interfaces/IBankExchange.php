<?php

namespace Nimp\CurrencyExchange\interfaces;

use Nimp\CurrencyExchange\exceptions\BankDataNotFoundException;
use DateTime;

interface IBankExchange
{
    /**
     * @param DateTime $date
     * @return array this array must be looks like ['USD' => 40, 'EUR' => 45 ...]
     * @throws BankDataNotFoundException
     */
    public function getExchangeRates(DateTime $date): array;

    /**
     * @return array this array must be looks like ['USD', 'EUR', 'UAH' ...]
     */
    public function getSupportedCurrencies(): array;
}