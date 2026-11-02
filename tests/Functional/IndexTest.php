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
}
