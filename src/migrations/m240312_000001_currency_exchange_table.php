<?php

namespace Nimp\CurrencyExchange\migrations;

use yii\db\Migration;

class m240312_000001_currency_exchange_table extends Migration
{
    protected string $tableName = 'currency_exchange';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'id_exchanger' => $this->integer()->unsigned()->notNull(),
            'date_time' => $this->dateTime()->notNull(),
            'currency_name' => $this->string(5)->notNull(),
            'currency_value' => $this->decimal(10, 5),
        ]);

        /** Эти два индекса гарантируют, что ни в разрезе даты, ни в разрезе обменника нельзя добавить одинаковую валюту больше одного раза */
        $this->createIndex(
            'idx-unique-date-currency',
            $this->tableName,
            ['date_time', 'currency_name'],
            true
        );

        $this->createIndex(
            'idx-unique-exchanger-currency',
            $this->tableName,
            ['id_exchanger', 'currency_name'],
            true
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}