<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFreshInstallationRedirectsToInstaller()
    {
        $response = $this->get('/');

        $response->assertRedirect('/install.php');
    }
}
