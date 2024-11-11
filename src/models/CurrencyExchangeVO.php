<?php

namespace Nimp\CurrencyExchange;

use JetBrains\PhpStorm\Pure;

class CurrencyExchangeVO
{
    public string $dateTime;
    public int $idExchanger;
    private array $_currencies;

    const ROUND_PRECISION = 5;

    /**
     * @param int $idExchanger
     * @param string $dateTime
     * @param array $_currencies this array most looks like ['EUR' => 30, 'USD' => 25 ...]
     */
    public function __construct(int $idExchanger, string $dateTime, array $_currencies)
    {
        $this->idExchanger = $idExchanger;
        $this->dateTime = $dateTime;
        $this->_currencies = $_currencies;
    }


    /**
     * @param $name
     * @return float|null
     */
    #[Pure] public function __get($name)
    {
        return $this->_currencies[$name] ?
            round($this->_currencies[$name], self::ROUND_PRECISION) :
            null;
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float $value
     * @return float|null
     */
    public function getCrossCourse(string $currencyFrom, string $currencyTo, float $value): float|null
    {
        return round(($this->$currencyFrom / $this->$currencyTo) * $value, self::ROUND_PRECISION);
    }

}