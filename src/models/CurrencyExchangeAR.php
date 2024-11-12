<?php

namespace Nimp\CurrencyExchange\models;

use Nimp\CurrencyExchange\models\CurrencyExchangeVO;
use DateTime;
use JetBrains\PhpStorm\Pure;
use Nimp\CurrencyExchange\exceptions\CurrencyExchangeException;
use Nimp\CurrencyExchange\interfaces\IStorage;
use yii\db\ActiveRecord;
use yii\db\Expression;

class CurrencyExchangeAR extends ActiveRecord implements IStorage
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'currency_exchange';
    }


    /**
     * {@inheritdoc}
     */
    #[Pure] public function rules(): array
    {
        return [
            [['id_exchanger', 'date_time', 'currency_name', 'currency_value'], 'required'],
            ['currency_value', 'number'],
            ['currency_name', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id_exchanger' => 'Id Exchanger',
            'date_time' => 'Date Time',
            'currency_name' => 'Currency name',
            'currency_value' => 'Currency value',
        ];
    }

    /**
     * @param int $id_exchanger
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function getCoursesById(int $id_exchanger): CurrencyExchangeVO
    {
        return $this->getCourses(['id_exchanger' => $id_exchanger]);
    }

    /**
     * @param DateTime $dateTime
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function getCoursesByDate(DateTime $dateTime): CurrencyExchangeVO
    {
        return $this->getCourses(['=', 'date_time', $dateTime->format('Y-m-d H:00:00')]);
    }

    /**
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function getLastCourses(): CurrencyExchangeVO
    {
        return $this->getCoursesById(self::getLastExchangerId());
    }

    /**
     * @throws CurrencyExchangeException
     */
    public function getCourses(?array $condition = null): CurrencyExchangeVO
    {
        $query = self::find()->select('id_exchanger, date_time, currency_name, currency_value');
        if ($condition) {
            $query->where($condition);
        } else {
            $query->where(['id_exchanger' => self::getLastExchangerId()]);
        }

        $currencies = [];
        $data = $query->all();
        if (empty($data)) {
            throw new CurrencyExchangeException();
        }
        foreach ($data as $currency) {
            $currencies[$currency->currency_name] = $currency->currency_value;
        }

        return new CurrencyExchangeVO(
            current($data)->id_exchanger,
            current($data)->date_time,
            $currencies
        );
    }


    /**
     * @return int
     */
    public static function getLastExchangerId(): int
    {
        return self::find()
            ->select(['id_exchanger' => new Expression('MAX(id_exchanger)')])
            ->asArray()
            ->scalar();
    }
}