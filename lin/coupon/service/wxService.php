<?php

class wxService
{

    public $wxConfig;
    public function __construct()
    {
        $this->wxConfig = utils::config('wxConfig');
    }

    public function jscode2session($code) {
        $wxConfig = $this->wxConfig;
        $requestData = [
            'appid' => $wxConfig['appId'],
            'secret' => $wxConfig['secret'],
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $wxUrl = $wxConfig['serverApiUrl'].'/sns/jscode2session';
        $wxResult = request::sendApiRequest($wxUrl,$requestData,'GET');
        if(isset($wxResult['openid']) && $wxResult['openid'] && isset($wxResult['session_key']) && $wxResult['session_key']) {
            return [
                'openId' => $wxResult['openid'],
                'sessionKey' => $wxResult['session_key'],
                'unionid' => isset($wxResult['unionid']) ? $wxResult['unionid'] : '',
            ];
        } else {
            return [];
        }
    }

    /*
     *  微信接口获取access_token，请不要直接调用这个，通过userservice 调用。
     */
    public function getAccessToken() {
        $wxConfig = $this->wxConfig;
        $requestData = [
            'grant_type' => 'client_credential',
            'appid' => $wxConfig['appId'],
            'secret' => $wxConfig['secret'],
        ];
        $wxUrl = $wxConfig['serverApiUrl'].'/cgi-bin/token';
        $wxResult = request::sendApiRequest($wxUrl,$requestData,'GET');
        if(isset($wxResult['access_token']) && $wxResult['access_token'] && isset($wxResult['expires_in']) && $wxResult['expires_in']) {
            return [
                'accessToken' => $wxResult['access_token'],
                'expiresIn' => $wxResult['expires_in']
            ];
        }
        return [];
    }


}

?>