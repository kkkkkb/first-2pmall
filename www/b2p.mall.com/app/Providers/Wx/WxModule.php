<?php

namespace App\Providers\Wx;

use App\Providers\Wx\Helper;
use DB;

/**
 * 微信
 * @author chentengfeng @create_at 2016-08-28  00:29:25
 */
class WxModule
{
    private $tables = null;
    /**
     * 获取表名等数据
     *
     * @return void
     * @author chentengfeng @create_at 2016-08-28  22:37:23
     */
    public function __construct(array $tables)
    {
        $this->tables = $tables;
    }

    /**
     * 将-分隔法转化为对应的get方法
     *
     * @return void
     * @author chentengfeng @create_at 2016-08-28  00:29:25
     */
    private function toolGetFun($fun)
    {
        return 'get'.str_replace(' ', '', ucwords(str_replace('-', ' ', $fun)));
    }

    /**
     * 获取微信用户信息
     *
     * @return mix(int, array) 若是已经存在用户则返回id, 若无则返回对应的用户信息或者错误信息
     * @author chentengfeng @create_at 2016-08-28  09:07:55
     */
    private function toolWxInfo($code)
    {
        $appid = config('wx.appid');
        $secret = config('wx.appsecret');
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
        $token = Helper::curlGet($token_url);
        $token = json_decode($token, true);
        //获取授权码失败
        if (isset($token['errcode'])) {
            return Helper::error(90000, '获取微信授权码失败');
        }

        //检测是否存在对应的openid
        $user_info  = DB::table($this->tables['user'])
                    ->select('id', 'avatar', 'nick_name')
                    ->whereExists(function ($query) {
                        $query->from($this->tables['user_wx'])
                              ->whereRaw($this->tables['user'] . '.id = ' . $this->tables['user_wx'] . '.user_id');
                    })
                    ->first();
        if (!empty($user_info)) {
            return $user_info;
        }

        $token['access_token'];
        $token['openid'];
        $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$token['access_token']}&openid={$token['openid']}&lang=zh_CN";
        $user_info = Helper::curlGet($info_url);
        if (isset($user_info['errcode'])) {
            return Helper::error(90001, '用户信息获取不到');
        }

        return $user_info;
    }

    /**
     * 回调地址获取
     * @param $controller 控制器
     * @param $fun 调用的方法(- 分隔方法)
     *
     * @return void
     * @author chentengfeng @create_at 2016-08-28  00:29:25
     */
    public function redirectUrl($controller, $fun)
    {
        //为空，或者自身时跳转到默认路由
        if (empty($controller) || empty($fun) || ($controller == 'wx' && $fun == 'user-info')) {
            $controller = config('wx.default_controller');
            $fun        = config('wx.default_fun');
        }
        
        try {
            $redirect_url = action('Wap\\'.ucfirst($controller).'Controller@'.$this->toolGetFun($fun));
        } catch (Exceptions $e) {
            //查找不到该路由则使用默认路由
            $redirect_url = action(ucfirst(Config::get('wx.default_controller')).'Controller@'.$this->toolGetFun(Config::get('wx.default_fun')));
        }

        return $redirect_url;
    }

    /**
     * 存储用户信息
     * @param $code 由链接跳转过来的code
     *
     * @return void
     * @author chentengfeng @create_at 2016-08-28  00:29:25
     */
    public function saveUserInfo($code)
    {
        $user_info = $this->toolWxInfo($code);
        if (isset($user_info['err_code'])) {
            Helper::log('user', json_encode($user_info));
            return $user_info;
        }

        $current_time = time();
        $insert = [
            'sex'        => $user_info['sex'],
            'source'     => 3,
            'avatar'     => $user_info,
            'reg_time'   => $current_time,
            'last_login' => $current_time,
            'user_name'  => $user_info['openid'],
            'nick_name'  => $user_info['nickname'],
        ];
        $id = DB::table($this->tables['user'])->insertGetId($insert);

        $insert = [
            'openid'     => $user_info['openid'],
            'nickname'   => $user_info['nick_name'],
            'sex'        => $user_info['sex'],
            'province'   => $user_info['province'],
            'city'       => $user_info['city'],
            'country'    => $user_info['country'],
            'headimgurl' => $user_info['headimgurl'],
            'privilege'  => implode(',', $user_info['privilege']),
        ];

        //只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段
        if (isset($user_info['unionid'])) {
            $insert['unionid'] = $user_info['unionid'];
            $insert['subscribe'] = 1;
        }

        DB::table($this->tables['user_wx'])->insertGetId($insert);

        Helper::saveLoginInfo($id, $user_info['headimgurl'], $user_info['nick_name']);
    }


    /**
     * 获取微信token
     *
     * @return void
     * @author chentengfeng @create_at 2016-09-01  08:19:18
     */
    /* //修改为使用overtrue
    public function getAccessToken()
    {
        $cache_key = '';
        $value = Cache::get($cache_key);
        if (!empty($value)) {
            return $value;
        }

        $appid = config('wx.appid');
        $secret = config('wx.appsecret');
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
        $result = json_decode(Helper::curlGet($url), true);
        if (isset($result['errcode'])) {
            Helper::log('access-token', json_encode($result));
            return null;
        }

        Cache::put($cache_key, $result['access_token'], $result['expires_in'] - 60);
        return $result['access_token'];
    }
     */
}


