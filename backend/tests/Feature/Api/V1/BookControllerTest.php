<?php

namespace Tests\Feature\Api\V1;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_index_full_list(): void
    {
        $this->createAndAuthenticateTestUser();

        $books = self::booksDataProvider(1);

        $request = $this->get('/api/v1/books');

        $request->assertStatus(200);

        $request->assertJson(['data' => $books]);
    }

    public function test_create_new_book(): void
    {

    }

    public function test_retrieve_single_book(): void
    {

    }

    public function test_update_single_book(): void
    {

    }

    public function test_delete_single_book(): void
    {

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

    public static function booksDataProvider(int $amount=1): array
    {
        return Book::factory($amount)->create()->toArray();
    }

    private function createAndAuthenticateTestUser(): void
    {
        $user = [
            'name' => 'TestBookController',
            'email' => 'testbookcontroller@testbookcontroller.test',
            'password' => bcrypt('TestBookController@Local'),
        ];

        $created = User::create($user);

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $created->id, 'hash' => sha1($created->email)]
        );

        $this->actingAs($created)->get($verificationUrl);

        $this->post('/api/v1/login', ['email' => $user['email'], 'password' => $user['password']]);

        $this->assertAuthenticated();
    }
}