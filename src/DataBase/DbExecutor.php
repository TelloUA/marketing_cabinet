<?php

namespace App\DataBase;
use mysqli;
use mysqli_result;

/*
 * Обʼєкт це зʼєднання з базою та наявний запит, треба виконати запит і повернути результат.
 * Хто має викливати клас і звідки треба передавати сюди запит $query? Є окремий клас на формування
 * Хто має виконувати запит execute()?
 */
class DbExecutor
{
    private ?mysqli_result $result;

    private bool $hasResult;

    private string $query;

    private mysqli $connection;

    private static string $SERVER_NAME = 'mysql';

    private static string $USER_NAME = 'myuser';

    private static string $PASSWORD = 'mypassword';

    private static string $DB_NAME = 'advertiser_cabinet';

    public const connectionParams = [
        'dbname' => 'advertiser_cabinet',
        'user' => 'root',
        'password' => 'root',
        'host' => 'localhost',
        'driver' => 'mysqli',
    ];

    public function __construct(bool $hasResult, string $query)
    {
        $this->hasResult = $hasResult;
        $this->query = $query;
    }

    public function execute(): void
    {
        $this->connect();
        if ($this->hasResult) {
            $this->result = $this->connection->query($this->query);
        } else {
            $this->connection->query($this->query);
            $this->result = null;
        }
        $this->close();
    }

    private function connect(): void
    {
        $this->connection = new mysqli(self::$SERVER_NAME, self::$USER_NAME, self::$PASSWORD, self::$DB_NAME);
        $this->connection->query("SET NAMES 'utf8'");
    }

    private function close(): void
    {
        $this->connection->close();
    }

    /**
     * @return mysqli_result|null
     */
    public function getResult(): ?mysqli_result
    {
        return $this->result;
    }


}