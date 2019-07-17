<?php

class indexController extends adminController
{
    public $adminMenuObj;

    public function __construct()
    {
        parent::__construct();
        $this->adminMenuObj = new adminMenuModel();
    }

    public function index()
    {
        request::jumpHeader(array('code'=>302,'url'=>'/admin/workItem/index'));

        $adminUserInfo = $this->checkAdminLogin();
        if (!$adminUserInfo) {
            $jumpUrl = utils::getUrl('admin/index/login');
            request::jumpHeader(array('code'=>301,'url'=>$jumpUrl));
        }
        $this->setView();
    }

    public function left($param)
    {
        $id = 27;
        if ($param) {
            $id = $param[0];
        }
        $menuList = $this->adminMenuObj->getChildMenuByIdCache($id);
        $data = array(
            'menuList' => $menuList
        );
        $this->setView($data);
    }

    public function top()
    {
        $id = request::getParam('menuid', 0);
        $menuList = $this->adminMenuObj->getChildMenuByIdCache($id);
        $adminUserObj = new adminUserModel();
        $adminId = utils::getSessionVal('user');
        $adminUserInfo = $adminUserObj->find($adminId);
        $adminGroup = $adminUserObj->group;
        $nowTime = time();
        $data = array(
            'menuList' => $menuList,
            'nowTime' => $nowTime,
            'adminUser' => $adminUserInfo,
            'adminGroup' => $adminGroup
        );
        $this->setView($data);
    }

    public function main()
    {
        $this->actionMenu = array(
            array(
                'name' => '更新菜单缓存',
                'url' => utils::getUrl('admin/menu/clear-menu-cache'),
            ),
        );
        $this->menuTitle = '系统信息';
        utils::pexit($_SERVER);

        $this->setView();
    }

    public function login()
    {
        if ($this->checkAdminLogin()) {
            $this->tip('您已登录！', utils::getUrl('admin'));
        }
        $this->setView();
    }

    public function loginIn()
    {
        $user = request::postParam('user', '');
        $password = request::postParam('password', '');
        $adminUserModel = new adminUserModel();
        $encryptPassword = $adminUserModel->createPassword($user, $password);
        $paramWhere = array(
            'field' => array(
                'id', 'userName', 'addTime', 'pstatus', 'updateTime'
            ),
            'where' => 'userName =? and password=? and pstatus=?',
            'param' => array($user, $encryptPassword, 1),
            'isRow' => true,
        );
        $userInfo = $adminUserModel->select($paramWhere);

        if ($userInfo) {
            utils::setSessionVal('user', $userInfo['id']);
            $this->tip('登录成功，即将进入ERP后台系统', utils::getUrl('admin'));
        } else {
            $this->tip('登录失败，请确认用户名和密码是否正确', utils::getUrl('admin'));
        }

    }

    public function loginOut()
    {
        utils::setSessionVal('user', '');
        $this->tip('您已退出ERP后台系统', utils::getUrl('admin/index/login'));
    }
}

?>