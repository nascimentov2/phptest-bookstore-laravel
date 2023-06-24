<?php

use Tests\TestCase;

class BookControllerTest extends TestCase
{
    /**
     * Test if all BookController method require authentication
     *
     * @dataProvider endPointsDataProvider
     * @param string $url
     * @param string $method
     *
     * @return void
     */
    public function test_ensure_all_endpoints_must_be_authenticated(string $method, string $url): void
    {
        $request = $this->$method($url);

        $request->assertStatus(200);

        $request->assertJson(['message' => 'Unauthenticated.']);
    }

    public static function endPointsDataProvider(): array
    {
        return 
        [
            'get /api/v1/books'      => ['get', '/api/v1/books'],
            'post /api/v1/books'     => ['post', '/api/v1/books'],
            'get /api/v1/books/1'    => ['get', '/api/v1/books/1'],
            'put /api/v1/books/1'    => ['put', '/api/v1/books/1'],
            'delete /api/v1/books/1' => ['delete', '/api/v1/books/1'],
        ];
    }
}