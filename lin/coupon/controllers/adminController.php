<?php

class adminController extends controller
{
    public $actionMenu;
    public $menuTitle;


    public function __construct($isCheckLogin = true)
    {
        parent::__construct();
        if($isCheckLogin) {
            $this->checkLogin();
        }
        $this->title = '管家服务平台';
    }

    public function checkLogin() {
        $sessionService = utils::getService('session');
        $adminUserIsLogin = $sessionService->getSessionValue('adminUser');
        if($adminUserIsLogin != 'isLogined') {
            request::jumpHeader(['url' => '/admin/user/index']);
        }
    }

    public function setView($data = array(), $template = '')
    {
        $this->actionMenu = $this->createActionMenu($this->actionMenu);
        $this->coustomData = array(
            'menuTitle' => $this->menuTitle,
            'actionMenu' => $this->actionMenu,
        );
        parent::setView($data, $template);
    }

    public function createActionMenu($actionMenuArr)
    {
        $menus = array();
        if (isset($actionMenuArr['name'])) {
            $actionMenuArr = array($actionMenuArr);
        }
        if ($actionMenuArr) {
            foreach ($actionMenuArr as $actionMenu) {
                $id = $class = '';
                $href = isset($actionMenu['url']) ? $actionMenu['url'] : 'javascript:;';
                if (isset($actionMenu['id'])) {
                    $id = 'id="' . $actionMenu['id'] . '"';
                } else {
                    $class = '';
                }
                if (isset($actionMenu['class'])) {
                    $class = 'class="' . $actionMenu['class'] . '"';
                } else {
                    $class = '';
                }
                $html = '<a href="' . $href . '" ' . $class . ' ' . $id . '>' . $actionMenu['name'] . '</a>';
                $menus[] = $html;
            }
        }
        return $menus;
    }

    public function checkAdminLogin()
    {
        $adminUserInfo = '';
        $sessionUser = utils::getSessionVal('user');
        if ($sessionUser) {
            $adminUserObj = new adminUserModel();
            $adminUserInfo = $adminUserObj->find($sessionUser);
        }
        return $adminUserInfo;
    }



    public function workItemList() {
        $workItemOrderService = utils::getService('workItemOrder');
        $workItemOrderService->getWorkItemOrderList();
    }



}

?>