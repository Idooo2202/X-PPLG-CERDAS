<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole {
    public function handle(Request $request, Closure $next, string ...$roles) {
        $userRole = session('user_role');
        // #region agent log
        if ($request->routeIs('kas.payment.toggle')) {
            @file_put_contents(
                base_path('debug-f13595.log'),
                json_encode([
                    'sessionId' => 'f13595',
                    'runId' => 'post-fix',
                    'hypothesisId' => 'H14',
                    'location' => 'app/Http/Middleware/CheckRole.php:handle',
                    'message' => 'CheckRole reached for kas.payment.toggle',
                    'data' => [
                        'path' => $request->path(),
                        'sessionRole' => $userRole,
                        'allowedRoles' => $roles,
                    ],
                    'timestamp' => (int) round(microtime(true) * 1000),
                ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
                FILE_APPEND
            );
        }
        // #endregion

        if (!$userRole || !in_array($userRole, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak! Kamu tidak memiliki izin.');
        }

        return $next($request);
    }
}