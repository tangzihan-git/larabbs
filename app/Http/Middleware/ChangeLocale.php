<?php

namespace App\Http\Middleware;

use Closure;

class ChangeLocale
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
        $language = $request->header('accept-language');//获取客户端语言设置
        if($language){
            \App::setLocale($language);
        }
        return $next($request);
    }
}
/**
 * 下载证书放置在服务器中
 * App获取APNS分配的deviceToken （应用唯一标识），服务器记录下deviceToken
 * 当执行消息推送时。服务器将deviceToken和要发送的消息提交给APNS，并使用证书签名
 *APNS根据devicetoken查找手机将消息推送 
 */