<?php

namespace MuhmdRaouf\LaravelParatest\Helper;

use MuhmdRaouf\LaravelParatest\Database\Connector;
use MuhmdRaouf\LaravelParatest\Database\PDOConnector;
use MuhmdRaouf\LaravelParatest\Database\DryRunConnector;

class ConfigHelper
{
    public static function isDryRun(bool $dryRun = false): bool
    {
        $isDryRun = (bool) $dryRun;

        if ($isDryRun) {
            info('[DRY] Running in dry-run.');
        }

        return $isDryRun;
    }

    public static function generateConfig($database): array
    {
        $connection = config('database.default');
        $configs = config("database.connections.$connection");
        $configs['database'] = $database ?: $configs['database'];

        return $configs;
    }

    public static function makeConnector(array $configs, bool $dryRun, $output): Connector
    {
        if ($dryRun === true) {
            return new DryRunConnector($output);
        }

        return PDOConnector::make($configs);
    }
}
