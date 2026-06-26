<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IaeApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // Sesuai IAE-T2: Header X-IAE-KEY berisi NIM mahasiswa.
        $nim = (string) (config('services.iae.nim') ?: '102022430022');

        $provided = (string) $request->header('X-IAE-KEY', '');

        if ($provided === '' || $provided !== $nim) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized: Invalid or missing X-IAE-KEY',
                'errors'  => null,
            ], 401);
        }

        // Atribut untuk kompatibilitas downstream (audit SOAP, event RabbitMQ).
        $request->attributes->set('iae_subject', $nim);
        $request->attributes->set('iae_roles', ['student']);

        return $next($request);
    }
}
