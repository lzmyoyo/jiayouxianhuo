<?php

class wxService
{

    public function jscode2session($code) {
        $wxConfig = utils::config('wxConfig');
        $requestData = [
            'appid' => $wxConfig['appId'],
            'secret' => $wxConfig['secret'],
            'js_code' => $code,
            'grant_type' => 'authorization_code',
        ];
        $wxUrl = $wxConfig['getSessionKeyUrl'];
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

}

?>