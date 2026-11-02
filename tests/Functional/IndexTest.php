<?php

declare(strict_types=1);

namespace Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IndexTest extends TestCase
{
    #[Test]
    public function indexLoads(): void
    {
        $res = $this->get('/');

        $body = (string) $res->getBody();

        $this->assertSame(200, $res->getStatusCode());
        $this->assertStringContainsString('Under construction', $body);
        $this->assertStringContainsString('Something new is on the way', $body);
    }

    #[Test]
    public function unknownRouteDisplaysNotFoundPage(): void
    {
        $res = $this->get('/page-that-does-not-exist');

        $body = (string) $res->getBody();

        $this->assertSame(404, $res->getStatusCode());
        $this->assertStringContainsString('Page not found', $body);
        $this->assertStringContainsString('href="/"', $body);
    }
}
