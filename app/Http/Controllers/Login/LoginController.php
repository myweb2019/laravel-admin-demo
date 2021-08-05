<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Request\Login\Login;
use App\Model\Manager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Lib\Helper;
use JWTAuth;

class LoginController extends Controller
{
    // 获取验证码
    public function getcode()
    {
        return response()->json([
            'status' => '200',
            'message' => '获取成功',
            // flat
            'url' => app('captcha')->create('flat', true),
        ]);
    }

    // 登录接口
    public function loginApi(Login $request)
    {
        $params = $request->all();
        // 验证验证码是否存在
        // $res = captcha_api_check($params['code'], $params['key'], 'flat');
        // if (!$res) return $this->sendErrorJson('验证码不正确');
        // 查询数据
        $user = Manager::where([
            'username' =>  $params['username'],
            'status' => '2'
        ])->first();
        // 验证用户是否存在
        if (!$user) {
            return Helper::sendErrorJson('用户不存在');
        }
        // 验证密码是否正确
        if (!Hash::check($params['password'], $user->password)) {
            return Helper::sendErrorJson('密码错误，请重新输入');
        }
        // redis 缓存的key
        $userTokenKey = "USER_TOKEN_STORE_KEY" . $user->id;
        // 判断Redis中是否存在
        if (Redis::exists($userTokenKey)) {
            $result = Redis::get($userTokenKey);
            return Helper::sendSuccessJson($result);
        } else {
            // 通过JWT验证用户，通过则返回token
            if (!$token = JWTAuth::fromUser($user)) {
                return Helper::sendErrorJson('用户身份验证失败');
            }
            // 获取token到期时间
            $expires_in = auth('jwtAuth')->factory()->getTTL() * 60;
            // 返回给客户端的数据格式
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => $expires_in
            ];
            // 如果需要用到当前登录的账户信息，可以缓存
            // $tokenInfo = [
            //     'user_id' => $user->id,
            //     'username' => $user->username,
            //     'mobile' => $user->mobile,
            //     'token' => $data
            // ];
            // 用户信息存入redis,一天内有效
            Redis::setex($userTokenKey, $expires_in * 60, json_encode($data, JSON_UNESCAPED_UNICODE));
            return Helper::sendSuccessJson($data);
        }
  

        // return Helper::sendSuccessJson([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'expires_in' => 3600 * 2
        // ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json();
    }

    public function getUserlist()
    {
        $user = Manager::all();
        $this->sendSuccessJson($user->toArray());
    }
}
