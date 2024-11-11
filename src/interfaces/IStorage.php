<?php

namespace Nimp\CurrencyExchange\interfaces;

use Nimp\CurrencyExchange\CurrencyExchangeVO;
use Nimp\CurrencyExchange\exceptions\CurrencyExchangeException;
use DateTime;

interface IStorage
{
    /**
     * @param int $id_exchanger
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function getCoursesById(int $id_exchanger): CurrencyExchangeVO;

    /**
     * @throws CurrencyExchangeException
     * @param DateTime $dateTime
     * @return CurrencyExchangeVO
     */
    public function getCoursesByDate(DateTime $dateTime): CurrencyExchangeVO;

    /**
     * @throws CurrencyExchangeException
     * @return CurrencyExchangeVO
     */
    public function getLastCourses(): CurrencyExchangeVO;

}