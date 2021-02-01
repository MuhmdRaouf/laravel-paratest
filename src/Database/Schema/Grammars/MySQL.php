<?php

namespace MuhmdRaouf\LaravelParatest\Database\Schema\Grammars;

class MySQL implements SQL
{
    public function compileCreateDatabase(array $options): string
    {
        $database = $options['database'];
        $charset = $options['charset'];
        $collation = $options['collation'];

        return "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET `$charset` COLLATE `$collation`;";
    }

    public function compileDropDatabase(string $database): string
    {
        return "DROP DATABASE IF EXISTS `$database`;";
    }
}
