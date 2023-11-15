<?php

namespace App\DataBase;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Tools\DsnParser;

class DbConnection
{
    private const DATABASE_URL = 'mysqli://myuser:mypassword@mysql/advertiser_cabinet';

    private array $connectionParams;

    public function __construct(DsnParser $dsnParser)
    {
        $this->connectionParams = $dsnParser
            ->parse(self::DATABASE_URL);
    }

    /**
     * @throws Exception
     */
    public function getConnection() {
        return DriverManager::getConnection($this->connectionParams);
    }
}