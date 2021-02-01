<?php

namespace MuhmdRaouf\LaravelParatest\Database;

use PDO;

class PDOConnector implements Connector
{
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function make(array $configs): PDOConnector
    {
        $driver = $configs['driver'];
        $host = $configs['host'] ?? '127.0.0.1';
        $username = $configs['username'];
        $password = $configs['password'];

        $host = "$driver:host=$host";
        $pdo = new PDO($host, $username, $password);

        return new static($pdo);
    }

    /**
     * @param string $sql
     *
     * @return mixed whatever the actual implementation returns, depending on the connector
     */
    public function exec(string $sql)
    {
        return $this->pdo->exec($sql);
    }
}

