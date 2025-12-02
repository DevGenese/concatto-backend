<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CorsTest extends TestCase
{
    public function test_options_request_is_handled_by_cors_middleware_before_throttling()
    {
        // Define a test route that would normally be throttled
        Route::middleware(['api', 'throttle:1,1'])->get('/test-cors-throttle', function () {
            return 'ok';
        });

        $origin = 'http://random-origin.com';

        // Send multiple OPTIONS requests to trigger potential throttling if it wasn't handled early
        for ($i = 0; $i < 10; $i++) {
            $response = $this->withHeaders([
                'Origin' => $origin,
                'Access-Control-Request-Method' => 'GET',
            ])->optionsJson('/api/test-cors-throttle');

            // Should be 204 No Content (standard for Preflight) or 200 OK depending on config,
            // but definitely NOT 429 Too Many Requests
            $response->assertStatus(204);
            $response->assertHeader('Access-Control-Allow-Origin', $origin);
            $response->assertHeader('Access-Control-Allow-Methods');
        }
    }

    public function test_cors_headers_are_present_on_simple_request()
    {
        $origin = 'http://random-origin.com';

        $response = $this->withHeaders([
            'Origin' => $origin,
        ])->getJson('/api/expenses'); // Using an existing route

        $response->assertHeader('Access-Control-Allow-Origin', $origin);
    }
}
