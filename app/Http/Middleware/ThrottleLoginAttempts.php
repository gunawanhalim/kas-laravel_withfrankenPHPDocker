<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleLoginAttempts
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle($request, Closure $next)
    {
        if ($this->limiter->tooManyAttempts($this->throttleKey($request), 3)) {
            return $this->buildResponse($this->limiter->availableIn($this->throttleKey($request)));
        }

        $response = $next($request);

        if ($this->limiter->hit($this->throttleKey($request), 1)) {
            // Increment the login attempts if the login fails
            // This will count the failed login attempts
        }

        return $response;
    }

    protected function throttleKey($request)
    {
        return mb_strtolower($request->input('username')) . '|' . $request->ip();
    }

    protected function buildResponse($seconds)
    {
        $response = new Response('Too Many Attempts.', 429);

        $response->headers->add(['Retry-After' => $seconds]);
    }
}
