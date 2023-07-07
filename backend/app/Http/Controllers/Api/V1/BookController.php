<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Book $book): JsonResource
    {
        $this->authorize(__FUNCTION__, $book);

        return BookResource::collection(Book::all());
    }

    /**
     * Display a paginated listing of the resource.
     */
    public function indexPaginated(Request $request, Book $book): JsonResource
    {   
        $this->authorize(__FUNCTION__, $book);

        return BookResource::collection(Book::paginate()->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request, Book $book): BookResource
    {
        $this->authorize(__FUNCTION__, $book);

        $created = Book::create($request->validated());

        return BookResource::make($created)->additional(['message' => __('Book created successfully.')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): BookResource
    {
        $this->authorize(__FUNCTION__, $book);

        return BookResource::make($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book): BookResource
    {
        $this->authorize(__FUNCTION__, $book);

        $book->update($request->validated());

        return BookResource::make($book)->additional(['message' => __('Book updated successfully.')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $this->authorize(__FUNCTION__, $book);
        
        $book->delete();

        return response()->json(['message' => __('Book deleted successfully.')]);
    }
}
