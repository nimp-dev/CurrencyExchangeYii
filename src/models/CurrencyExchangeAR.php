<?php

namespace Nimp\CurrencyExchange\models;

use DateTime;
use Nimp\CurrencyExchange\exceptions\CurrencyExchangeException;
use Nimp\CurrencyExchange\interfaces\IStorage;

class CurrencyExchangeAR implements IStorage
{
    /**
     * @inheritDoc
     */
    public function getCoursesById(int $id_exchanger): CurrencyExchangeVO
    {
        return new CurrencyExchangeVO(1, (new \DateTime())->format('Y-m-d 00:00:00'), ['UAH' => 1]);
    }

    /**
     * @inheritDoc
     */
    public function getCoursesByDate(DateTime $dateTime): CurrencyExchangeVO
    {
        return new CurrencyExchangeVO(1, (new \DateTime())->format('Y-m-d 00:00:00'), ['UAH' => 1]);
    }

    /**
     * @inheritDoc
     */
    public function getLastCourses(): CurrencyExchangeVO
    {
        return new CurrencyExchangeVO(1, (new \DateTime())->format('Y-m-d 00:00:00'), ['UAH' => 1]);
    }
}