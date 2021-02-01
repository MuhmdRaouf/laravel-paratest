<?php

namespace MuhmdRaouf\LaravelParatest\Console;

use Illuminate\Console\Command;
use MuhmdRaouf\LaravelParatest\Helper\ConfigHelper;
use MuhmdRaouf\LaravelParatest\Database\Schema\Builder;
use MuhmdRaouf\LaravelParatest\Database\Schema\GrammarFactory;

class DbCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create
        {--dry-run}
        {--database= : The database name to test}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GrammarFactory $grammars)
    {
        if (app()->environment('testing')) {
            $isDryRun = ConfigHelper::isDryRun($this->option('dry-run'));
            $configs = ConfigHelper::generateConfig($this->option('database'));

            $builder = new Builder(
                ConfigHelper::makeConnector($configs, $isDryRun, $this->output),
                $grammars
            );

            $builder->createDatabase($configs);

            $database = $configs['database'];
            $this->info("Database $database created successfully.");
        } else {
            $this->warn('You are not in testing environment.');
        }
    }
}
