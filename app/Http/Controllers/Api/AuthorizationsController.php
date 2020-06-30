<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\AuthorizationRequest;

class AuthorizationsController extends Controller
{
    //第三方登录
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        $driver = \Socialite::driver($type);//授权驱动
        /*
            1.根据code获取access_token
            2.根据access_token获取用户信息（uniondid,openid）
            3.根据微信id查找是否有对应用户
            4.判断用户是否存在
            5.[创建用户]/返回用户
        */
        try {
            if ($code = $request->code) {
                //根据授权码获取token
                $response = $driver->getAccessTokenResponse($code);
                $token = Arr::get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if ($type == 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }
            //根据Token获取用户信息
            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        switch ($type) {
        case 'weixin':
            //获取unionid
            $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;

            //unionid或openid是否存在，存在即已经注册
            if ($unionid) {
                $user = User::where('weixin_unionid', $unionid)->first();
            } else {
                $user = User::where('weixin_openid', $oauthUser->getId())->first();
            }

            // uniondid或openid不存在，默认创建一个用户
            if (!$user) {
                $user = User::create([
                    'name' => $oauthUser->getNickname(),
                    'avatar' => $oauthUser->getAvatar(),
                    'weixin_openid' => $oauthUser->getId(),
                    'weixin_unionid' => $unionid,
                ]);
            }

            break;
        }

        $token= auth('api')->login($user);

        return $this->respondWithToken($token)->setStatusCode(201);
    }
    //本地登录
    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;
        //过滤username变量，是否为email
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;
        
        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            // throw new AuthenticationException('用户名或密码错误');
            //修改为语言切换版本
            throw new AuthenticationException(trans('auth.failed'));
        }
        return $this->respondWithToken($token)->setStatusCode(201);
        
    }
    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return response(null, 204);
    }
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}