<?php

namespace Nimp\CurrencyExchange;

use DateTime;
use Nimp\CurrencyExchange\CurrencyExchangeVO;
use Nimp\CurrencyExchange\exceptions\CurrencyExchangeException;
use Nimp\CurrencyExchange\interfaces\IStorage;
use yii\base\Component;

class CurrencyExchangeLoader extends Component
{
    protected string $storageClass;

    protected string $storageApiClass;

    /**
     * @param string $storage
     */
    public function setStorageClass(string $storage): void
    {
        if (!isset($this->storageClass)) {
            $this->storageClass = $storage;
        }
    }

    /**
     * @param string $storage
     * @return void
     */
    public function setStorageApiClass(string $storage): void
    {
        if (!isset($this->storageApiClass)) {
            $this->storageApiClass = $storage;
        }
    }

    /**
     * @param string $type
     * @return IStorage
     */
    public function get(string $type): IStorage
    {
        return match ($type) {
            'API' => new $this->storageApiClass(),
            default => new $this->storageClass(),
        };
    }

    /**
     * @param string $type
     * @param int|null $idExchanger
     * @param string|null $date
     * @return CurrencyExchangeVO
     * @throws CurrencyExchangeException
     */
    public function load(
        string  $type = 'default',
        ?int    $idExchanger = null,
        ?string $date = null
    ): CurrencyExchangeVO
    {
        $storage = $this->get($type);
        if ($idExchanger) {
            $payload = $storage->getCoursesById($idExchanger);
        } elseif ($date) {
            $payload = $storage->getCoursesByDate(new DateTime($date));
        } else {
            $payload = $storage->getLastCourses();
        }

        return $payload;
    }
}