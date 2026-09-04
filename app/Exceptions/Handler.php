<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session
     * on validation exceptions.
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

    /**
     * Handle unauthenticated users.
     */
    protected function unauthenticated(
        $request,
        AuthenticationException $exception
    ) {
        // API / AJAX / JSON request
        if ($request->expectsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorised',
            ], 401);
        }

        $guards = $exception->guards();

        // POS
        if (in_array('pos', $guards)) {
            return redirect()->route('pos.login');
        }

        // Admin
        if (in_array('admin', $guards)) {
            return redirect()->route('login');
        }

        // Default
        return redirect()->route('login');
    }
}