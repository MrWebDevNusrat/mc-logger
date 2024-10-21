<?php

namespace MC\Logger\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogMiddleware
{
    public function getIpAddress()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userId =  (auth()->user() ? auth()->id() : null);
        $routeName = $request->path();

        $response = $next($request);

        $http_code = (string)$response->getStatusCode();

        $requestData = [
            'user_id' => $userId,
            'request' => json_encode($request->all()),
            'response' => json_encode($response),
            'ip' => $this->getIpAddress(),
            'method' => $request->method(),
            'route'=>$routeName,
            'http_code' => $http_code,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('logs')->insert($requestData);

        Log::info('Request and Response:', [
            'user_id' => $userId,
            'request' => $requestData,
            'response' => $response,
        ]);

        return $response;
    }
}
