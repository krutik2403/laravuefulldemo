<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadMethodCallException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            // detect previous instance
            if ($exception->getPrevious() instanceof TokenExpiredException) {    
                $token = JWTAuth::getToken();
                $new_token = JWTAuth::refresh($token);            
                return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage(), 
                                    'new_token' => $new_token
                                ], 200);
            }
            else if ($exception->getPrevious() instanceof TokenInvalidException) {
                return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
            }
            else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
            } else if ($exception instanceof \Symfony\Component\Debug\Exception\FatalErrorException) {
                return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
            }
        } 

        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
        }

        if ($exception instanceof TokenBlacklistedException) {
            return response()->json([
                                    'status'    => 0, 
                                    'message'   => $exception->getMessage()
                                ], 200);
        }



        if ($request->is('api/*')) {
            //Add log about the error
            Log::error(" Message:" . $exception->getMessage() . " StackTrace:" . $exception->getTraceAsString());

            if ($exception instanceof HttpException) {
                return response()->json([
                                'status'    => 0, 
                                'message'   => Response::$statusTexts[$exception->getStatusCode()]
                            ], $exception->getStatusCode());
            } else if ($exception instanceof ModelNotFoundException) {

                $modelNamespaceString = $exception->getModel();
                $modelName = ExceptionHandlerUtility::getClassNameFromNamespaceString($modelNamespaceString);

                return response()->json([
                                'status'    => 0, 
                                'message'   => "$modelName entity not found!"
                            ], 200);
            } else if ($exception instanceof QueryException) {

                $errorCode = $exception->errorInfo[1];
                // MYSQL Error Code: http://dev.mysql.com/doc/refman/5.7/en/error-messages-server.html#error_er_dup_entry
                if ($errorCode == 1062) {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => "Similar resource already exists"
                            ], 200);
                }
            } else if ($exception instanceof FatalThrowableError) {

                return response()->json([
                                'status'    => 0, 
                                'message'   => "Something went wrong with our system!"
                            ], 200);
            } else if ($exception instanceof APIException) {
                if ($exception instanceof NotInServiceableZoneException) {
                    
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                } elseif ($exception instanceof APIValidationException) {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                } else if ($exception instanceof APINotFoundException) {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                } else if ($exception instanceof APIResourceConflictException) {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                }else if($exception instanceof APIForbiddenException)
                {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                } else if ($exception instanceof APIUnauthorizedException) {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                } else {
                    return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 200);
                }
            } else if ($exception instanceof JWTException) {

                if ($exception instanceof TokenInvalidException) {

                    Log::info("Exception Handler: " . "Auth Token is invalid!");

                    return response()->json([
                                'status'    => 0, 
                                'message'   => "Authentication token is invalid!"
                            ], 200);
                }
            }
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                                'status'    => 0, 
                                'message'   => Response::$statusTexts[$exception->getStatusCode()]
                            ], $exception->getStatusCode() );
        } else if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                                'status'    => 0, 
                                'message'   => Response::$statusTexts[Response::HTTP_NOT_FOUND]
                            ], Response::HTTP_NOT_FOUND);
        } else if ($exception instanceof ValidationException && $exception->getResponse()) {
            return response()->json([
                                'status'    => 0, 
                                'message'   => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY]
                            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            //TODO resolve FatalThrowableError
        } else if ($exception instanceof FatalThrowableError) {
            return response()->json([
                                'status'    => 0, 
                                'message'   => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR]
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } else if ($exception instanceof \BadMethodCallException || $exception instanceof NotFoundHttpException) {
            return response()->json([
                                'status'    => 0, 
                                'message'   => $exception->getMessage()
                            ], 500);
        }

        return parent::render($request, $exception);
    }
}