<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\TrustHosts;
use Tests\CreatesApplication;
use Tests\TestCase;

class TrustHostsTest extends TestCase
{
    use CreatesApplication;

    public function test_hosts_returns_all_subdomains_from_application(): void
    {
        $app_hosts = [];

        $app = $this->createApplication();

        $trustHostsMiddleware = new TrustHosts($app);

        $trusted_hosts = $trustHostsMiddleware->hosts();

        if ($host = parse_url($app['config']->get('app.url'), PHP_URL_HOST)) {
            $app_hosts = ['^(.+\.)?'.preg_quote($host).'$'];
        }

        $this->assertEqualsCanonicalizing($app_hosts, $trusted_hosts);
    }
}