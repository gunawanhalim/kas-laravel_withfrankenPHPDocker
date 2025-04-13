<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HandleMaxExecutionTime
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Exception $e) {
            if ($e instanceof HttpException && $e->getStatusCode() === 500 && strpos($e->getMessage(), 'Maximum execution time') !== false) {
                return redirect()->route('login')->with('error', 'Session timeout, please login again.');
            }

            throw $e;
        }
    }
}
