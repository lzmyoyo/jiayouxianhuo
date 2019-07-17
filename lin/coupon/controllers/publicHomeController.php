<?php

class publicHomeController extends controller
{
    public function __construct(){
        parent::__construct();
        $jumpData = [
            'url' => '/mobile',
            'code' => 302
        ];
        request::jumpHeader($jumpData);
        $this->title = 'couponsharer';
        //$this->checkouLocalTime();
        $this->initLanguage();
    }

	/*
	 * 如果没有设置时间差cookie。需要跳转页面设置cookie
	 */
    private function checkouLocalTime() {
    	$diffTime = cookie::getCookie('diffTime');
    	if($diffTime === '' &&  strtolower(ACTIONNAME) != 'localhosttime' && strtolower(ACTIONNAME) != 'settimediff') {
			//header('Location:/index/localhostTime?backUrl='.base64_encode($this->url));
			exit;
		}
	}

    public function initLanguage() {
		$language = request::getParam('language','');
		if(empty($language)) {
			if(in_array(strtolower(SERVERNAME_PATH),array('ar','en'))) {
				$language  = strtolower(SERVERNAME_PATH);
			}
		}
		if(empty($language)) {
			$language = cookie::getCookie('language');
			if(empty($language)) {
				$language = 'en';
			} else {
				$language = '';
			}
		}
		if($language) {
			$language = strtolower($language);
			if(in_array($language,array('en','ar'))) {
				cookie::setCookie('language',$language,30*24*3600);
			}
		}
	}

     public function getRequestData()
    {
        $jsonDataArr = array();
        $jsonDataStr = file_get_contents("php://input");
        if($jsonDataStr) {
            $jsonDataArr = json_decode($jsonDataStr,true);
        }
        return $jsonDataArr;
    }
    /*
             public function getRequestData()
             {
                 $jsonDataArr = array();
                 $jsonDataStr = file_get_contents("php://input");
                 if($jsonDataStr) {
                     $jsonDataArr = json_decode($jsonDataStr,true);
                 }
                 if($_GET['pr']) {
                     $jsonDataArr = $_GET;
                 }
                 return $jsonDataArr;
             }
        */


	public function checkToken($userId, $requestToken) {
	    $userService = utils::getService('user');
	    $userInfo = $userService->getUserInfo($userId);
    	if($userInfo['token'] != $requestToken) {
			$this->reponseJsonData(
				array('code' => 400,'message'=>'无权访问')
			);
		}
	}
    public function responseJson($data) {
        header('Content-Type:application/json; charset=utf-8');
        $jsonData = json_encode($data,true);
        echo $jsonData;
        exit;
    }

    public function requestParamData($keyName,$dataArr) {
	    if(isset($dataArr[$keyName])) {
	        return $dataArr[$keyName];
        }
        return '';
    }

    public function setView($data = array(), $template = '')
    {
        $sessionService = utils::getService('session');
        $userId = $sessionService->getUserLogin();
        $userIsLogined = cookie::getCookie('userIsLogined');
        $this->coustomData['userId'] = $userId;
        $this->coustomData['userIsLogined'] = $userIsLogined;
        parent::setView($data, $template);
    }


}

?>