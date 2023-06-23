<?php

namespace Tests\Feature\Api\V1;

use App\Models\Book;
use App\Models\User;
use Database\Factories\BookFactory;
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

        $books = self::booksDataProvider(10);

        $request = $this->get('/api/v1/books');

        $request->assertStatus(200);

        $request->assertJson(['data' => $books]);
    }

    public function test_create_new_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = [
            'name'  => fake()->words(rand(3,7), true),
            'isbn'  => fake()->isbn13(),
            'value' => fake()->randomFloat(2,1,1000)
        ];

        $request = $this->post('/api/v1/books', $book);

        $request->assertStatus(201);

        $created = Book::where($book)->get()->toArray();

        $this->assertCount(1, $created);
    }

    public function test_retrieve_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $books = self::booksDataProvider(10);

        $book_id = rand(0,9); //we created 10 books so the data provider returns keys from 0 to 9 in array

        $request = $this->get('/api/v1/books/'.$books[$book_id]['id']);

        $request->assertStatus(200);
    }

    public function test_update_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = self::booksDataProvider(1);

        $book_data = [
            'name'  => 'Test Book Update',
            'isbn'  => '9781012345123',
            'value' => '10.50'
        ];

        $request = $this->put('/api/v1/books/'.$book[0]['id'], $book_data);

        $request->assertStatus(200);

        $updated = Book::where($book_data)->get()->toArray();

        $this->assertCount(1, $updated);
    }

    public function test_delete_single_book(): void
    {
        //$this->createAndAuthenticateTestUser();
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
        $books = Book::factory($amount)->create()->toArray();
        
        return $books;
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