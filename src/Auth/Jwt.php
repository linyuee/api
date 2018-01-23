<?php
namespace Linyuee\Auth;
/**
 * Created by PhpStorm.
 * User: yuelin
 * Date: 2018/1/20
 * Time: 下午6:57
 */

class Jwt
{
    private static $expires = 60; //设置单次过期时间(分钟)
    private static $appExpires = 60*24*30;

    public static function createToken(array $session_data = array(),$type = 'app')
    {

        $session_key = guid();


        $device = 'web';
        $time = time();
        $expires_time = $time + (self::$expires * 60);
        $secret_key = '';

        if ($type == 'app') {
            $device = 'app';
            $expires_time = $time + (self::$appExpires * 60);
            $secret_key = guid();
        }
        $session_data['device'] = $device;
        $session_data['create_at'] = $time;      //创建时间
        $session_data['expires_time'] = $expires_time;  //过期时间
        $session_data['sk'] = $secret_key;

        $data = array(
            'session_key' => $session_key
        );
        //过期时间
        $session_expires_time = $device == 'web' ? self::$expires : self::$appExpires;

        \Cache::add('session:' . $session_key, $session_data, $session_expires_time);
        return ['token' => JWT::encode($data, config('app.key')), 'sk' => $secret_key];
    }
}

