<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleRequests extends ThrottleRequests
{
    use ApiResponser;
    /**
     * Create a 'too many attempts' response.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {

        $response =  $this->errorResponse('Too Many Attempts.',429);

        $retryAfter = $this->limiter->availableIn($key);

        return $this->addHeaders(
            $response, $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );
    }

}