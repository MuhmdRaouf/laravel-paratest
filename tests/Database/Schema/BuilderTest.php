<?php

namespace MuhmdRaouf\LaravelParatest\Database\Schema;

use Mockery;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
use MuhmdRaouf\LaravelParatest\Database\Connector;

class BuilderTest extends TestCase
{
    public function setUp(): void
    {
        $this->connector = new FakeConnector();
        $this->grammars = Mockery::mock(GrammarFactory::class, [
            'make' => new FakeGrammar(),
        ]);

        $this->builder = new Builder(
            $this->connector,
            $this->grammars
        );
    }

    /** @test */
    public function test_it_creates_database()
    {
        $options = [
            'driver' => 'fake_driver',
            'database' => 'fakedb',
        ];

        $this->builder->createDatabase($options);

        $this->connector->assertExecuted('CREATE DATABASE IF NOT EXISTS `fakedb`;');
    }

    /** @test */
    public function test_it_drops_database()
    {
        $options = [
            'driver' => 'fake_driver',
            'database' => 'fakedb',
        ];

        $this->builder->dropDatabase($options);

        $this->connector->assertExecuted('DROP DATABASE IF EXISTS `fakedb`;');
    }

    /** @test */
    public function test_it_recreates_databases()
    {
        $options = [
            'driver' => 'fake_driver',
            'database' => 'fakedb',
        ];

        $this->builder->recreateDatabase($options);

        $this->connector->assertExecutedQueriesCount(2);
        $this->connector->assertExecuted('CREATE DATABASE IF NOT EXISTS `fakedb`;');
        $this->connector->assertExecuted('DROP DATABASE IF EXISTS `fakedb`;');
    }
}

class FakeConnector implements Connector
{
    public $executedSqlCommands = [];

    public function exec(string $sql)
    {
        $this->executedSqlCommands[] = $sql;

        return true;
    }

    public function assertExecuted(string $sql)
    {
        $matches = array_filter($this->executedSqlCommands, function ($executed) use ($sql) {
            return $executed === $sql;
        });

        PHPUnit::assertGreaterThanOrEqual(
            1,
            $matches,
            "Failed asserting that query $sql was executed."
        );
    }

    public function assertExecutedQueriesCount(int $expectedCount)
    {
        PHPUnit::assertCount($expectedCount, $this->executedSqlCommands);
    }
}

class FakeGrammar implements Grammars\SQL
{
    public function compileCreateDatabase(array $options): string
    {
        $database = $options['database'];
        return "CREATE DATABASE IF NOT EXISTS `$database`;";
    }

    public function compileDropDatabase(string $database): string
    {
        return "DROP DATABASE IF EXISTS `$database`;";
    }
}
