<?php

namespace Tests\Feature\Api\V1;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_full_list(): void
    {
        $this->createAndAuthenticateTestUser();

        $books = self::booksDataProvider(10);

        $request = $this->get(route('books.index'));

        $request->assertStatus(200);

        $request->assertJson(['data' => $books]);
    }

    public function test_create_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = [
            'name'  => fake()->words(rand(3,7), true),
            'isbn'  => fake()->isbn13(),
            'value' => fake()->randomFloat(2,1,1000)
        ];

        $request = $this->post(route('books.store'), $book);

        $request->assertStatus(201);

        $created = Book::where($book)->get()->toArray();

        $this->assertCount(1, $created);
    }

    public function test_retrieve_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $books = self::booksDataProvider(10);

        $book_id = rand(0,9); //we created 10 books so the data provider returns keys from 0 to 9 in array

        $request = $this->get(route('books.show', $books[$book_id]['id']));

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

        $request = $this->put(route('books.update', $book[0]['id']), $book_data);

        $request->assertStatus(200);

        $updated = Book::where($book_data)->get()->toArray();

        $this->assertCount(1, $updated);
    }

    public function test_delete_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = self::booksDataProvider(1);

        $exists = Book::where($book[0])->get()->toArray();

        $this->assertCount(1, $exists);

        $request = $this->delete(route('books.destroy', $book[0]['id']));

        $request->assertStatus(200);

        $not_exists = Book::where($book[0])->get()->toArray();

        $this->assertCount(0, $not_exists);
    }
    public static function booksDataProvider(int $amount=1): array
    {
        $books = Book::factory($amount)->create()->toArray();
        
        return $books;
    }
}