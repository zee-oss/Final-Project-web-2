<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        $user = auth()->user();
        if ($user->isOwner()) {
            return $next($request);
        }

        $request->merge(['_user_branch_id' => $user->branch_id]);

        return $next($request);
    }
}