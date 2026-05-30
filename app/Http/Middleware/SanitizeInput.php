<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Sanitize all inputs
        $sanitized = array_map(function ($value) {
            if (is_string($value)) {
                // Remove HTML tags and JavaScript content
                $value = strip_tags($value);
                $value = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $value);
            }
            return $value;
        }, $request->all());

        // Replace the request data with sanitized data
        $request->merge($sanitized);

        return $next($request);
    }
}
