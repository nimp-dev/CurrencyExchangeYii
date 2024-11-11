<?php

namespace Nimp\CurrencyExchange\models;

use DateTime;
use Nimp\CurrencyExchange\interfaces\IStorage;

class CurrencyExchangeAPI implements IStorage
{

    /**
     * @inheritDoc
     */
    public function getCoursesById(int $id_exchanger): CurrencyExchangeVO
    {
        // TODO: Implement getCoursesById() method.
    }

    /**
     * @inheritDoc
     */
    public function getCoursesByDate(DateTime $dateTime): CurrencyExchangeVO
    {
        // TODO: Implement getCoursesByDate() method.
    }

    /**
     * @inheritDoc
     */
    public function getLastCourses(): CurrencyExchangeVO
    {
        // TODO: Implement getLastCourses() method.
    }
}