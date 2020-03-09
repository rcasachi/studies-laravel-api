<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandlerTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_an_exception_into_a_json_api_spec_error_response() {
        $handler = app(Handler::class);
        $request = Request::create('/test', 'GET');

        $request->headers->set('accept', 'application/vnd.api+json');
        $exception = new \Exception('Test exception');

        $response = $handler->render($request, $exception);

        TestResponse::fromBaseResponse($response)
            ->assertJson([
                'errors' => [
                    [
                        'title'   => 'Exception',
                        'details' => 'Test exception',
                    ]
                ]
            ])->assetStatus(500);
    }

    /**
     * @test
     */
    public function it_converts_an_http_exception_into_a_json_api_spec_error_response() {
        $handler = app(Handler::class);

        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'aaplication/vnd.api+json');

        $exception = new HttpException(404, 'Not Found');
        $response = $handler->render($request, $exception);

        TestResponse::fromBaseResponse($response)->assetJson([
            'errors' => [
                [
                    'title'   => 'Http Exception',
                    'details' => 'Not Found',
                ]
            ]
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function it_converts_an_unauthenticated_exception_into_a_json_api_spec_error_response() {
        $handler = app(Handler::class);

        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'aaplication/vnd.api+json');

        $exception = new AuthenticationException();
        $response = $handler->render($request, $exception);

        TestResponse::fromBaseResponse($response)->assetJson([
            'errors' => [
                [
                    'title'   => 'Unauthenticated',
                    'details' => 'You are not authenticated',
                ]
            ]
        ])->assertStatus(404);
    }
}
