<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;

abstract class BaseController
{
    public function __construct(
        protected Twig $view,
        protected ResponseInterface $response
    ) {}

    protected function render(string $template, array $data = []): ResponseInterface
    {
        return $this->view->render($this->response, $template, $data);
    }
}
