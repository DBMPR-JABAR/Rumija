<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($request->expectsJson()){

            if($exception instanceof UnauthorizedHttpException) {
                $response = [
                    'status' => 'false',
                    'data' => [
                        'message' => 'Unauthorized'
                    ]
                ];
                return response()->json($response, 200);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 'false',
                    'data' => [
                        'message' => 'Entry for '.str_replace('App\\Model\\', '', $exception->getModel()).' not found']
                    ], 200);
            }
        }else{
           if($exception instanceof TokenMismatchException){
               return redirect()->route('login');
           }
        }

        return parent::render($request, $exception);
    }
}
