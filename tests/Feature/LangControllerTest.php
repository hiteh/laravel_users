<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LangControllerTest extends TestCase
{
    /**
     * Test controller response status.
     *
     * @return void
     */
    public function testGetRequestRedirectBack()
    {
        $response = $this->withHeaders(['HTTP_REFERER' => '/'])->get('/lang');

        $response->assertStatus(302);

        $response->assertLocation('/');
    }

    /**
     * Test controller response status.
     *
     * @return void
     */
    public function testPostRequestRedirectBack()
    {

        $response = $this->withHeaders(['HTTP_REFERER' => '/'])->post('/lang');

        $response->assertStatus(302);

        $response->assertLocation('/');
    }

    /**
     * Test set session language.
     *
     * @return void
     */
    public function testSetSessionLanguage()
    {

        $response_pl = $this->post('/lang', ['lang' => 'pl']);

        $response_pl->assertSessionHas('lang', 'pl');

        $response_en = $this->post('/lang', ['lang' => 'en']);

        $response_en->assertSessionHas('lang', 'en');

    }
}
