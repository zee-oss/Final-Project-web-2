<?php
namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class ActivityLogMiddleware
{
    // Method HTTP yang akan dilog
    protected array $logMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, $response): void
    {
        if (!auth()->check()) return;
        if (!in_array($request->method(), $this->logMethods)) return;

        // Jangan log request yang gagal (4xx/5xx)
        if ($response->getStatusCode() >= 400) return;

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => $request->method() . ' ' . $request->path(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}