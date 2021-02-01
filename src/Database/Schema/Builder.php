<?php

namespace MuhmdRaouf\LaravelParatest\Database\Schema;

use MuhmdRaouf\LaravelParatest\Database\Connector;

class Builder
{
    public function __construct(Connector $connector, GrammarFactory $grammars)
    {
        $this->connector = $connector;
        $this->grammars = $grammars;
    }

    public function createDatabase(array $options)
    {
        $driver = $options['driver'];
        $grammar = $this->grammars->make($driver);

        return $this->connector->exec(
            $grammar->compileCreateDatabase($options)
        );
    }

    public function dropDatabase(array $options)
    {
        $driver = $options['driver'];
        $database = $options['database'];
        $grammar = $this->grammars->make($driver);

        return $this->connector->exec(
            $grammar->compileDropDatabase($database)
        );
    }

    public function recreateDatabase(array $options): void
    {
        $this->dropDatabase($options);
        $this->createDatabase($options);
    }
}
