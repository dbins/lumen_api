<?php
namespace App\Exceptions;

use Throwable;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof  HttpException){
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];
            return \response()->json($message, $code)->header('Content-Type', 'application/json');
        }
        if($exception instanceof ModelNotFoundException){
            $model = strtolower(class_basename($exception->getModel()));
			return \response()->json("Does exist instance of {$model} with this id", Response::HTTP_NOT_FOUND)->header('Content-Type', 'application/json');
        }
        if($exception instanceof AuthorizationException){
			return \response()->json($exception->getMessage(), Response::HTTP_FORBIDDEN)->header('Content-Type', 'application/json');
        }
        if($exception instanceof AuthenticationException){
			return \response()->json($exception->getMessage(), Response::HTTP_UNAUTHORIZED)->header('Content-Type', 'application/json');
        }
        if($exception instanceof ValidationException){
            $errors = $exception->errors();
			return \response()->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY)->header('Content-Type', 'application/json');
        }
        if($exception instanceof ClientException){
            $message = $exception->getResponse()->getBody();
            $code = $exception->getCode();
            return \response()->json($message, $code)->header('Content-Type', 'application/json');
        }
        if(env('APP_DEBUG', false)) {
            return parent::render($request, $exception);
        }
		return \response()->json('unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR)->header('Content-Type', 'application/json');
    }
}
