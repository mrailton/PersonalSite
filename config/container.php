<?php

declare(strict_types=1);

use App\Helpers\ViteHelper;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Slim\Views\Twig;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

use App\Controllers\IndexController;
use Twig\TwigFunction;

return [
    ResponseInterface::class => \DI\get(Response::class),

    'settings' => [
        'app_env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'database_url' => $_ENV['DATABASE_URL'] ?? '',
        'secret_key' => $_ENV['SECRET_KEY'] ?? '',
        'templates_path' => __DIR__ . '/../templates',
    ],

    Twig::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');
        $twig = Twig::create($settings['templates_path'], ['cache' => false]);

        $isDev = $settings['app_env'] === 'development';
        $viteHelper = new ViteHelper($isDev);

        $environment = $twig->getEnvironment();
        $environment->addFunction(new TwigFunction('vite', function ($entry) use ($viteHelper) {
            return $viteHelper->renderTags($entry);
        }, ['is_safe' => ['html']]));

        return $twig;
    },

    EntityManagerInterface::class => function (ContainerInterface $c) {
        $settings = $c->get('settings');

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [__DIR__ . '/../src/Entity'],
            isDevMode: $settings['debug'],
        );

        $dbUrl = $settings['database_url'];

        $parsed = parse_url($dbUrl);

        $connectionParams = [
            'driver' => 'pdo_mysql',
            'host' => $parsed['host'] ?? 'localhost',
            'port' => $parsed['port'] ?? 3306,
            'dbname' => ltrim($parsed['path'] ?? '', '/'),
            'user' => $parsed['user'] ?? '',
            'password' => $parsed['pass'] ?? '',
            'charset' => 'utf8mb4',
        ];

        $connection = DriverManager::getConnection($connectionParams, $config);

        return new EntityManager($connection, $config);
    }
];
