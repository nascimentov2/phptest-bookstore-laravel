<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    
    Route::middleware(['auth:sanctum'])->group(function(){
        
        Route::get('books/paginated', [BookController::class, 'indexPaginated'])->name('books.paginated');
        Route::apiResource('/books', BookController::class);
    });
});

require __DIR__.'/auth.php';
