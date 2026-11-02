<?php

declare(strict_types=1);

use App\Middleware\SessionMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Csrf\Guard;
use Slim\Exception\HttpNotFoundException;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app): void {
    $container = $app->getContainer();
    $settings = $container instanceof ContainerInterface ? $container->get('settings') : [];
    $debug = (bool) ($settings['app']['debug'] ?? false);

    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));

    $app->add(Guard::class);

    $app->addRoutingMiddleware();

    $app->addBodyParsingMiddleware();

    $app->add(SessionMiddleware::class);

    $errorMiddleware = $app->addErrorMiddleware($debug, true, true);
    $errorMiddleware->setErrorHandler(
        HttpNotFoundException::class,
        static function (
            ServerRequestInterface $request,
            Throwable $exception,
            bool $displayErrorDetails,
            bool $logErrors,
            bool $logErrorDetails,
        ) use ($container): ResponseInterface {
            $response = $container->get(ResponseFactoryInterface::class)->createResponse(404);

            return $container->get(Twig::class)->render($response, '404.html.twig');
        },
    );
};
