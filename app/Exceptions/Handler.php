<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function report(Throwable $exception)
{
    if ($this->shouldReport($exception)) {
        // Log to custom error log
        Log::channel('error')->error($exception->getMessage(), [
            'exception' => $exception,
        ]);
    }

    parent::report($exception);


}
public function render($request, Throwable $e){
    dd($e->getMessage());
}

}
