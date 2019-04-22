<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */

//    very important
    public function render($request, Exception $exception)
    {
        // message error
        if ($exception instanceof ValidationException)
        {
            return $this->convertValidationExceptionToResponse($exception,$request);
        }
        //for not found user or model
        if ($exception instanceof ModelNotFoundException)
        {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exits any {$modelName} with specified identificator",'404');
        }
        // auth
        if ($exception instanceof AuthenticationException)
        {
            $this->unauthenticated($request ,$exception);
        }
        //auth
        if ($exception instanceof AuthenticationException)
        {
            $this->errorResponse($exception->getMessage(),403);
        }
        // ENTER url wrong
        if ($exception instanceof NotFoundHttpException)
        {
            return $this->errorResponse("The specified URL cannot be Found","404");
        }
        // ENTER method url wrong
        if ($exception instanceof MethodNotAllowedHttpException)
        {
            return $this->errorResponse("The specified Method for the requetes is invalid","405");
        }
        // Http
        if ($exception instanceof HttpException)
        {
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }
        // error database
        if ($exception instanceof  QueryException)
        {
            $errorCode =  $exception->errorInfo[1];
            if ($errorCode ==  1451)
            {
                return $this->errorResponse('Cannot remove  this resource permanently . It is related with any other resource',409);
            }
        }
        if (config('app.debug'))
        {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected  Exception',500);

    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $this->errorResponse('Unauthenticated','401');
//        if ($request->expectsJson()) {
//            return response()->json(['error' => 'Unauthenticated.'], 401);
//        }
//
//        return redirect()->guest(route('login'));
    }
    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);


    }
}
