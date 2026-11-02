<?php

declare(strict_types=1);

use Monolog\Level;

$rootDir = dirname(__DIR__);

$resolvePath = static function (string $path) use ($rootDir): string {
    if (
        $path === ':memory:'
        || str_starts_with($path, '/')
        || str_starts_with($path, '\\')
        || (strlen($path) > 2 && ctype_alpha($path[0]) && $path[1] === ':')
    ) {
        return $path;
    }

    return $rootDir . '/' . ltrim($path, '/\\');
};

$env = static function (string $name, mixed $default = null): mixed {
    $value = $_ENV[$name] ?? $_SERVER[$name] ?? getenv($name);

    return $value === false || $value === null ? $default : $value;
};

return [
    'settings' => [
        'app' => [
            'name' => $env('APP_NAME', 'Slim Starter'),
            'env' => $env('APP_ENV', 'development'),
            'debug' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
        ],

        'twig' => [
            'path' => $rootDir . '/resources/views',
            'options' => [
                'cache' => $env('TWIG_CACHE', false) === 'true' ? $rootDir . '/var/cache/twig' : false,
                'debug' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
                'auto_reload' => true,
            ],
        ],

        'doctrine' => [
            'dev_mode' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
            'cache_dir' => $rootDir . '/var/cache/doctrine',
            'metadata_dirs' => [$rootDir . '/src/Entity'],
            'connection' => [
                'driver' => $env('DB_DRIVER', 'pdo_mysql'),
                'path' => $env('DB_DRIVER', 'pdo_mysql') === 'pdo_sqlite'
                    ? ($env('DB_PATH') !== null ? $resolvePath($env('DB_PATH')) : $rootDir . '/var/app.sqlite')
                    : null,
                'host' => $env('DB_HOST', '127.0.0.1'),
                'port' => (int) $env('DB_PORT', 3306),
                'dbname' => $env('DB_NAME', 'slim_app'),
                'user' => $env('DB_USER', 'root'),
                'password' => $env('DB_PASSWORD', ''),
                'charset' => 'utf8mb4',
            ],
            'migrations' => [
                'table_storage' => [
                    'table_name' => 'doctrine_migration_versions',
                    'version_column_name' => 'version',
                    'version_column_length' => 191,
                    'executed_at_column_name' => 'executed_at',
                    'execution_time_column_name' => 'execution_time',
                ],
                'migrations_paths' => [
                    'App\Migrations' => $rootDir . '/migrations',
                ],
                'all_or_nothing' => true,
                'transactional' => true,
                'check_database_platform' => true,
                'organize_migrations' => 'none',
            ],
        ],

        'logger' => [
            'name' => 'app',
            'path' => $env('LOG_PATH') !== null ? $resolvePath($env('LOG_PATH')) : $rootDir . '/var/log/app.log',
            'level' => filter_var($env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN) ? Level::Debug : Level::Info,
        ],
    ],
];
