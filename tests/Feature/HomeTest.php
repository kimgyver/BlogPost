<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomePageIsWorking()
    {
        $response = $this->get('/');
        $response->assertSeeText('Welcome to Laravel!!');
    }

    public function testContactPageIsWorking()
    {
        $response = $this->get('/contact');
        $response->assertSeeText('Contact');
    }
}
