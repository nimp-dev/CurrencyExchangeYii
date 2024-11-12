<?php

namespace Nimp\CurrencyExchange\models;

use DateTime;
use Nimp\CurrencyExchange\exceptions\BankDataNotFoundException;
use Nimp\CurrencyExchange\exceptions\CurrencyExchangeException;
use Nimp\CurrencyExchange\interfaces\IBankExchange;
use Nimp\CurrencyExchange\interfaces\IStorage;
use Yii;

class CurrencyExchangeAPI implements IStorage
{
    /**
     * @var IBankExchange[] $banks
     */
    private array $banks;

    public function __construct()
    {
        foreach (Yii::$app->params['banks_used'] as $class => $data) {
            $this->banks[] = Yii::createObject($class, $data);
        }
    }

    /**
     * @param DateTime $dateNow
     * @return array
     */
    public function loadCoursesFromBanks(DateTime $dateNow): array
    {
        $allRates = [];

        foreach ($this->banks as $bank) {
            try {
                $rates = $bank->getExchangeRates($dateNow);
                $allRates = array_merge($allRates, $rates);
            }catch (BankDataNotFoundException $e) {
                Yii::error($e->getMessage()  , 'update-currency-exchange');
            }
        }

        return $allRates;
    }


    /**
     * @param DateTime $dateTime
     * @return CurrencyExchangeVO
     */
    public function getCoursesByDate(DateTime $dateTime): CurrencyExchangeVO
    {
        $dataBunk = $this->loadCoursesFromBanks($dateTime);
        $idExchanger = CurrencyExchangeAR::getLastExchangerId() + 1;
        $dateTime = $dateTime->format('Y-m-d H:00:00');
        return new CurrencyExchangeVO($idExchanger, $dateTime, $dataBunk);
    }

    /**
     * @return CurrencyExchangeVO
     */
    public function getLastCourses(): CurrencyExchangeVO
    {
        $dateTime = new DateTime('now');
        $dataBunk = $this->loadCoursesFromBanks($dateTime);
        $idExchanger = CurrencyExchangeAR::getLastExchangerId() + 1;
        $dateTime = $dateTime->format('Y-m-d H:00:00');
        return new CurrencyExchangeVO($idExchanger, $dateTime, $dataBunk);
    }

    /**
     * @param int $id_exchanger
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function getCoursesById(int $id_exchanger): CurrencyExchangeVO
    {
        throw new CurrencyExchangeException('This method does not work for API storage.');
    }

    /**
     * @return array
     */
    public static function getSupportedCurrencies(): array
    {
        $currencies = [];
        foreach ((new self())->banks as $bank) {
            $currencies = array_merge($currencies, $bank->getSupportedCurrencies());
        }
        return $currencies;
    }
}