<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface;

class IndexController extends BaseController
{
    public function __invoke(): ResponseInterface
    {
        return $this->render('index.twig', []);
    }
}