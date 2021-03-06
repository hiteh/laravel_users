<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Middleware\LangSwitch;
use Illuminate\Http\Request;

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

    /**
     * Test language switch middleware.
     *
     * @return void
     */
    public function testLangSwitchMiddleware()
    {
        $request = new Request;
        $middleware = new LangSwitch;
        $this->post( '/lang', ['lang' => 'pl'] );

        $middleware->handle( $request, function ( $req ) {
            $this->assertEquals( 'pl', app()->getLocale() );
        });

        $this->post( '/lang', ['lang' => 'en'] );

        $middleware->handle( $request, function ( $req ) {
            $this->assertEquals( 'en', app()->getLocale() );
        });

    }
}
