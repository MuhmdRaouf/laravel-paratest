<?php

namespace MuhmdRaouf\LaravelParatest\Database\Schema;

use RuntimeException;

class GrammarFactory
{
    private static $availableOptions = [
        'mysql' => Grammars\MySQL::class,
        'pgsql' => Grammars\PgSQL::class,
    ];

    public function make(string $driver): Grammars\SQL
    {
        if (!array_key_exists($driver, static::$availableOptions)) {
            throw new RuntimeException("Unknown driver $driver");
        }

        $grammar = static::$availableOptions[$driver];

        return new $grammar;
    }
}
