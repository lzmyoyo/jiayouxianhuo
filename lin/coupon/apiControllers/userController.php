<?php
	class userController extends apiController
	{
		public function login()
	    {
            $wxService = utils::getService('wx');
            $code = request::getParam('code');
            $wxResult = $wxService->jscode2session($code);
            if($wxResult) {
                $userService = utils::getService('user');
                $userToken = $userService->createUserToken($wxResult['openId'],$wxResult['sessionKey']);
                $userData = [
                    'wxOpenId' => $wxResult['openId'],
                    'wxSessionKey' => $wxResult['sessionKey'],
                    'userToken' => $userToken,
                ];
                $userInfo = $userService->saveUserInfo($userData);
                $userData = [
                    'userToken' => $userToken,
                    'nickName' => $userInfo['nickName'],
                    'avatarUrl' => $userInfo['avatarUrl'],
                ];
                $this->reponseJsonDataNew(200,['userInfo'=>$userData],'success');
            }
            $this->reponseJsonDataNew(400,[],'wx login error');
	    }

	    public function saveUserInfo() {
            $userToken = request::getInputParam('userToken');
            $userInfo = request::getInputParam('userInfo');
            if($userToken && $userInfo) {
                $userService = utils::getService('user');
                $userData = [
                    'userToken' => $userToken,
                    'nickName' => $userInfo['nickName'],
                    'avatarUrl' => $userInfo['avatarUrl']
                ];
                $returnData = $userService->saveUserInfoByToken($userToken,$userData);
                $this->reponseJsonDataNew(200,['userInfo'=>$returnData],'success');
            } else {
                $this->reponseJsonDataNew(400,[],'success');
            }

        }

        public function aa() {
            $userService = utils::getService('user');
            echo $userService->getAccessToken();
        }

	}
?>