<?php

namespace App\DataBase;


/*
 * Класс, який формує запит (але хто його буде визивати?)
 * Йому потрібний тип запиту, таблиця, поля, ?значення, ?умови.
 * Класс має віддавати готову фінальний запит
 */
class QueryFormer
{
    private string $queryType;

    private string $table;

    private array $mappingFields;

    private array $mappingData;

    private string $resultQuery;

    public function __construct(string $queryType, array $mapping)
    {
        $this->queryType = $queryType;
        $this->mappingFields = $mapping;

        switch ($queryType) {
            case 'SELECT':
                // тут треба поля що вибираються та умови
                break;
            case 'UPDATE':
                // тут треба поля що оновлюються, значення та умови
                break;
            case 'INSERT':
                // тут треба поля що вставляються та значення
                break;
            default:
                //
        }
    }

    /*
     * select - треба мапити усі поля
     * insert - треба мапити усі поля
     * update - треба мапити потрібні поля
     */

    /**
     * @param array $mapping
     */
    public function setMapping(array $mapping): void
    {
        $this->mappingFields = $mapping;
    }
}