<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render(mixed $request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => __('Entry not found')], 404);
        }
        
        if($exception instanceof ValidationException){
            return response()->json([
                'message' => $exception->errors()], 400);
        }

        if($exception instanceof AuthenticationException){
            return response()->json([
                'message' => $exception->getMessage()], 401);
        }

        return response()->json(['message' => $exception->getMessage()]);
    }
}
