<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IaeApiKey
{
    public function handle(Request $request, Closure $next)
    {

        $nim = '102022430022';

        if ($request->header('X-IAE-KEY') !== $nim) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid API Key',
                'errors' => null
            ], 401);
        }

        // Set request attributes to mock SSO/JWT attributes for downstream compatibility (SOAP audit, RabbitMQ event logging)
        $request->attributes->set('iae_subject', $nim);
        $request->attributes->set('iae_roles', ['student']);

        return $next($request);
    }
}
