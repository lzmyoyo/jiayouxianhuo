<?php

class apiController extends controller
{

    public function __construct()
    {
        parent::__construct();
        $paramData = request::requestInputParam();
        $userToken = isset($paramData['userToken']) && $paramData['userToken'] ? $paramData['userToken'] : '';
        $userService = utils::getService('user');
        $userInfo = $userService->getUserInfoByToken($userToken);
        utils::$userInfo = $userInfo;
    }

    public function setView($data = array(), $template = '')
    {
        parent::setView($data, $template);
    }
}

?>