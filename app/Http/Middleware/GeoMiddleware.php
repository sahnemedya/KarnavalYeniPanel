<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GeoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // AI botları için özel headers
        if ($this->isAIBot($request)) {
            $response->headers->set('X-Robots-Tag', 'index, follow, max-snippet:-1, max-image-preview:large');
            $response->headers->set('X-Content-Type', 'text/html; charset=utf-8');
        }

        return $response;
    }

    private function isAIBot(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        $aiBots = [
            'chatgpt-user',
            'gptbot',
            'claude-web',
            'anthropic-ai',
            'perplexitybot',
            'google-extended',
            'gemini',
            'bingbot',
            'copilot'
        ];

        foreach ($aiBots as $bot) {
            if (str_contains($userAgent, $bot)) {
                return true;
            }
        }

        return false;
    }
}
