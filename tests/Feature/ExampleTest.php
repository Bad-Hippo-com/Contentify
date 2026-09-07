<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFreshInstallationRedirectsToInstaller()
    {
        $route = app('router')->getRoutes()->getByName('home');

        $this->assertNotNull($route);
        $action = $route->getAction('uses');
        $this->assertInstanceOf(\Closure::class, $action);

        $response = $action();

        $this->assertStringEndsWith('/install.php', $response->getTargetUrl());
    }
}
