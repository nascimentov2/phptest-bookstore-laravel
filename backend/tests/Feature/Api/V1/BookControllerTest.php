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
        
        $json = $request->content(); //the endpoint should return json that contains all books

        $books_from_json = json_decode($json, true)['data']; //data is the default wrap key returned by resource

        $this->assertEqualsCanonicalizing($books, $books_from_json);
    }

    public function test_create_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = Book::factory()->definition();

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

        $book_data = Book::factory()->definition();

        $request = $this->put(route('books.update', $book[0]['id']), $book_data);

        $request->assertStatus(200);

        $updated = Book::where($book_data)->get()->toArray();

        $this->assertCount(1, $updated);
    }

    public function test_delete_single_book(): void
    {
        $this->createAndAuthenticateTestUser();

        $book = self::booksDataProvider(1);
        $book_id = $book[0]['id'];

        $exists = Book::where(['id' => $book_id])->get()->toArray();

        $this->assertCount(1, $exists);

        $request = $this->delete(route('books.destroy', $book[0]['id']));

        $request->assertStatus(200);

        $not_exists = Book::where(['id' => $book_id])->get()->toArray();

        $this->assertCount(0, $not_exists);

        $request = $this->get(route('books.show', $book_id));

        $request->assertStatus(404);
    }
    public static function booksDataProvider(int $amount=1): array
    {
        $books = Book::factory($amount)->create()->toArray();
        
        return $books;
    }
}