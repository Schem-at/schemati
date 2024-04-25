<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WhiteListedIp;

class IPWhitelist
{
    public function handle($request, Closure $next)
    {
        $ipWhitelistEnabled = config('app.ip_whitelist.enabled');

        if (!$ipWhitelistEnabled) {
            return $next($request);
        }

        $whitelistedIPRanges = WhiteListedIp::pluck('ip')->toArray();
        $incomingIP = $request->getClientIps();
        dd($incomingIP);

        foreach ($whitelistedIPRanges as $range) {
            if ($this->ipMatchesRange($incomingIP, $range)) {
                return $next($request);
            }
        }
        \Log::info('Unauthorized access attempt from IP: ' . $incomingIP . ' at ' . now());
        abort(403, 'Unauthorized access');
    }

    private function ipMatchesRange($ip, $range)
    {
        $rangeParts = explode('.', $range);
        $ipParts = explode('.', $ip);
        foreach ($rangeParts as $key => $part) {
            if ($part == '*') {
                continue;
            }
            if ($part != $ipParts[$key]) {
                return false;
            }
        }
        return true;
    }
}
