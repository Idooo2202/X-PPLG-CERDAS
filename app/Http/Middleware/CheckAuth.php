<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth {
    public function handle(Request $request, Closure $next) {
        // #region agent log
        if ($request->routeIs('kas.payment.toggle')) {
            @file_put_contents(
                base_path('debug-f13595.log'),
                json_encode([
                    'sessionId' => 'f13595',
                    'runId' => 'post-fix',
                    'hypothesisId' => 'H13',
                    'location' => 'app/Http/Middleware/CheckAuth.php:handle',
                    'message' => 'CheckAuth reached for kas.payment.toggle',
                    'data' => [
                        'path' => $request->path(),
                        'hasSessionUserId' => (bool) session('user_id'),
                    ],
                    'timestamp' => (int) round(microtime(true) * 1000),
                ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
                FILE_APPEND
            );
        }
        // #endregion
        if (!session('user_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu!');
        }
        return $next($request);
    }
}