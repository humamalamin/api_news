<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response as BaseResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Code coverage of this class is not necessary
 *
 * @codeCoverageIgnore
 */
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
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // If the request API call
        if ($this->isApiCall($request)) {
            $return = $this->getJsonResponseForException($exception);
        } else {
            $return = parent::render($request, $exception);
        }

        return $return;
    }

    public function getJsonResponseForException(Exception $exception)
    {
        if ($this->isFatalException($exception)) {
            $response = [
                'status' => BaseResponse::HTTP_INTERNAL_SERVER_ERROR,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_INTERNAL_SERVER_ERROR,
                    'detail' => 'Internal Server Error',
                ]]
            ];

            $result = $this->jsonResponse(
                $response,
                BaseResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        } else if ($this->isModelNotFoundException($exception)) {
            $result = $this->jsonResponse([
                'status' => BaseResponse::HTTP_NOT_FOUND,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_NOT_FOUND,
                    'detail' => $exception->getMessage()
                ]],
            ], BaseResponse::HTTP_NOT_FOUND);
        } else if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $result = $this->jsonResponse([
                'status' => BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_UNPROCESSABLE_ENTITY,
                    'detail' => $exception->errors()
                ]],
            ], BaseResponse::HTTP_UNPROCESSABLE_ENTITY);
        } else if ($this->isHttpNotFoundException($exception)) {
            $result = $this->jsonResponse([
                'status' => BaseResponse::HTTP_NOT_FOUND,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_NOT_FOUND,
                    'detail' => 'Page Not Found'
                ]],
            ], BaseResponse::HTTP_NOT_FOUND);
        } else if ($this->isAuthenticationException($exception)) {
            $result = $this->jsonResponse([
                'status' => BaseResponse::HTTP_UNAUTHORIZED,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_UNAUTHORIZED,
                    'detail' => 'Unauthenticated'
                ]],
            ], BaseResponse::HTTP_UNAUTHORIZED);
        } else {
            $result = $this->jsonResponse([
                'status' => BaseResponse::HTTP_BAD_REQUEST,
                'data' => null,
                'errors' => [[
                    'status' => BaseResponse::HTTP_BAD_REQUEST,
                    'detail' => $exception->getMessage()
                ]]
            ], BaseResponse::HTTP_BAD_REQUEST);
        }

        return $result;
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     *
     * @SuppressWarnings("unused")
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/v'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        return strpos($request->getUri(), '/api') !== false;
    }

    protected function isFatalException($exception)
    {
        return $exception instanceof \Symfony\Component\Debug\Exception\FatalThrowableError;
    }

    protected function isModelNotFoundException($exception)
    {
        return $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException;
    }

    protected function isHttpNotFoundException($exception)
    {
        return $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    }

    protected function isAuthenticationException($exception)
    {
        return $exception instanceof \Illuminate\Auth\AuthenticationException;
    }
}
