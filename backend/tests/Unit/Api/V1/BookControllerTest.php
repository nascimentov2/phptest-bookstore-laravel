<?php

namespace Tests\Unit\Api\V1;

use Tests\TestCase;

class BookControllerTest extends TestCase
{
    /**
     * Test if all BookController method require authentication
     *
     * @dataProvider endPointsDataProvider
     * @param string $url
     * @param string $method
     * @param mixed $parameters
     *
     * @return void
     */
    public function test_ensure_all_endpoints_must_be_authenticated(string $method, string $url, mixed $parameters=''): void
    {
        $request = $this->$method( route($url, $parameters) );

        $request->assertStatus(401);

        $request->assertJson(['message' => 'Unauthenticated.']);
    }

    public static function endPointsDataProvider(): array
    {
        return 
        [
            'get books.index'       => ['get', 'books.index'],
            'post books.store'      => ['post', 'books.store'],
            'get books.show'        => ['get', 'books.show', 1],
            'put books.update'      => ['put', 'books.update', 1],
            'delete books.destroy'  => ['delete', 'books.destroy', 1],
        ];
    }
}