<?php

class userService extends linservice
{

    public $modelName = 'user';

    public function __construct(){
        parent::__construct($this->modelName);
    }


    public function createUserToken($openId,$sessionKey) {
        $md5Str = $openId . $sessionKey . rand(100000,999999);
        return md5($md5Str);
    }

    //根据openId 保存用户信息
    public function saveUserInfo($userData) {
        $wxOpenId = $userData['wxOpenId'];
        $userModel = new userModel();
        $userInfo = $userModel->getUserInfoByAppId($wxOpenId);
        $nickName = $avatarUrl = '';
        if($userInfo) {
            unset($userData['wxOpenId']);
            $userId = $userInfo['id'];
            $userModel->save($userData,'wxOpenId = ?',[$wxOpenId]);
            $nickName = $userInfo['nickName'];
            $avatarUrl = $userInfo['avatarUrl'];
        } else {
            $userId = $userModel->insert($userData);
        }
        return [
            'nickName' => $nickName,
            'avatarUrl' => $avatarUrl,
            'userId' => $userId
        ];

    }
    //根据userToken 保存用户信息
    public function saveUserInfoByToken($userToken,$userData) {
        $userModel = new userModel();
        $userInfo = $userModel->getUserInfoByToken($userToken);
        if($userInfo) {
            $userId = $userInfo['id'];
            $userData['id'] = $userId;
            $userModel->save($userData);
            $nickName = $userData['nickName'];
            $avatarUrl = $userData['avatarUrl'];
            return [
                'nickName' => $nickName,
                'avatarUrl' => $avatarUrl,
                'userId' => $userId
            ];
        } else {
            return [];
        }
    }

    public function getUserInfoByToken($userToken) {
        $userModel = new userModel();
        return $userModel->getUserInfoByToken($userToken);
    }

    public function getAccessToken() {
        $accessTokenModel = new accessTokenModel();
        $serverTokenResult = $accessTokenModel->getToken();
        $accessToken = '';
        $isRestToken = true;
        $tokenId = 0;
        if($serverTokenResult && $serverTokenResult['accessToken']) {
            $tokenId = $serverTokenResult['id'];
            $nowTime = time() - 600;
            $tokenLastTime = $serverTokenResult['addTime'] + $serverTokenResult['expiresIn'];
            if($nowTime < $tokenLastTime) {
                $accessToken = $serverTokenResult['accessToken'];
                $isRestToken = false;
            }
        }
        if($isRestToken) {
            $wxService = utils::getService('wx');
            $wxResult = $wxService->getAccessToken();
            if($wxResult) {
                $accessToken = $wxResult['accessToken'];
                $saveData = [
                    'accessToken' =>  $wxResult['accessToken'],
                    'expiresIn' =>  $wxResult['expiresIn'],
                    'addTime' => time()
                ];
                if($tokenId) {
                    $saveData['id'] = $tokenId;
                    $accessTokenModel->save($saveData);
                } else {
                    $accessTokenModel->insert($saveData);
                }
            }
        }
        return $accessToken;
    }


}

?>