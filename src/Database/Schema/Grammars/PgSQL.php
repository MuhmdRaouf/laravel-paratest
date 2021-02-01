<?php

namespace MuhmdRaouf\LaravelParatest\Database\Schema\Grammars;

class PgSQL implements SQL
{
    public function compileCreateDatabase(array $options): string
    {
        $database = $options['database'];
        $charset = $options['charset'];
        $collation = $options['collation'] ?? 'en_US.utf8';

        return "CREATE DATABASE `$database` ENCODING '$charset' LC_COLLATE '$collation';";
    }

    public function compileDropDatabase(string $database): string
    {
        return "DROP DATABASE IF EXISTS `$database`;";
    }
}
