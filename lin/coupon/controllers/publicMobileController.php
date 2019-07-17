<?php

class publicMobileController extends controller
{

    public $isShowHeader = true;
    public $isShowFooder = false;

    public $menuActive = [
        'itemActive' => '',
        'addItemActive' => '',
        'meActive' => '',
    ];
    public $headerMenu = [
        'more' => false,
        'back' => false,
        'add' => false,
        'addImage' => false,
        'showChat' => false
    ];

    public function __construct(){
        parent::__construct();
        $this->title = '我的购物管家';
    }
    public function setView($data = array(), $template = '')
    {
        $this->coustomData['isShowHeader'] = $this->isShowHeader;
        $this->coustomData['isShowFooder'] = $this->isShowFooder;
        $this->coustomData['menuActive'] = $this->menuActive;
        $this->coustomData['headerMenu'] = $this->headerMenu;
        $sessionService = utils::getService('session');
        $userId = $sessionService->getUserLogin();
        $this->coustomData['userId'] = $userId;

        parent::setView($data, $template);
    }


    public function sendApiRequest($path,$param = [],$method = 'POST', $retruenData = false) {
        $apiUrl = utils::config('apiUrl');
        if(empty($method)) {
            $method = 'POST';
        }
        $resultJson = utils::sendApiRequest($apiUrl.$path, $param, $method);
        $resultArr = json_decode($resultJson, true);
        if(isset($resultArr['code'])) {
            if($retruenData) {
                return $resultArr['data'];
            }
            return $resultArr;
        } else {
            return false;
        }
    }
}

?>