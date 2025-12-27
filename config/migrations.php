<?php

declare(strict_types=1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

$containerDefinitions = require __DIR__ . '/container.php';
$container = new DI\Container($containerDefinitions);

$entityManager = $container->get(EntityManagerInterface::class);

$config = new PhpFile(__DIR__ . '/migrations.config.php');

return DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));
