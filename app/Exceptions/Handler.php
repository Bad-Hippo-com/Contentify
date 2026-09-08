<?php namespace App\Exceptions;

use ErrorException;
use Config;
use Response;
use View;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use MsgException;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Preserve Laravel's authentication redirects and validation responses.
        if ($exception instanceof \Illuminate\Validation\ValidationException ||
            $exception instanceof \Illuminate\Auth\AuthenticationException ||
            $exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            return parent::render($request, $exception);
        }
        // Laravel wraps any exceptions thrown in views in an error exception so we have to unwrap it
        // @see https://github.com/laravel/ideas/issues/956
        if ($exception instanceof ErrorException and
            $exception->getPrevious() and $exception->getPrevious() instanceof MsgException) {
            /* @var $innerException MsgException */
            $innerException = $exception->getPrevious();
            return $innerException->render($request);
        }

        if (! Config::get('app.debug')) { // If we are in debug mode we do not want to override Laravel's error output
            $exception = $this->prepareException($exception);
            if ($this->isHttpException($exception)) {
                $status = $exception->getStatusCode();
                if ($request->expectsJson()) {
                    return parent::render($request, $exception);
                }
                return response()->view($status === 404 ? 'error_not_found' : 'error', ['status' => $status], $status, $exception->getHeaders());
            }
            if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return Response::make(View::make('error_not_found'), 404);
            }

            return Response::make(View::make('error'), 500);
        }

        return parent::render($request, $exception);
    }
}
