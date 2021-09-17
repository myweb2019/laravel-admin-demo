<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 校验登录令牌
        $tokenString = (string)$request->header("Authorization");
        if ($tokenString === "") {
            return response()->json(['msg' => 'token无效', 'status' => '422'], 422);
        }

        // 解码
        $decode = base64_decode(explode('.', $tokenString)[1]);
        // 获取token中的用户id
        $userId = json_decode($decode)->sub;
        //判断token是否在缓存中
        $redisKey = 'USER_TOKEN_STORE_KEY' . $userId;
        if (Redis::exists($redisKey)) {
            return $next($request);
        }else {
            return response()->json(['msg' => 'token无效', 'status' => '422'], 422);
        }
    }
}
