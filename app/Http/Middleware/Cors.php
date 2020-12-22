<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * OptionsCorsResponse middleware - add CORS headers if request method OPTIONS
 */
class Cors
{
    /**
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        /* @var $response Response */
        $response = $next($request);
        if (!$request->isMethod('OPTIONS')) {
            return $response;
        }
        $allow = $response->headers->get('Allow'); // true list of allowed methods
        if (!$allow) {
            return $response;
        }
        $headers = [
            'Access-Control-Allow-Methods' => $allow,
            'Access-Control-Max-Age' => 3600,
            'Access-Control-Allow-Headers' => 'X-Requested-With, Origin, X-Csrftoken, Content-Type, Accept',
        ];
        return $response->withHeaders($headers);
    }
}
