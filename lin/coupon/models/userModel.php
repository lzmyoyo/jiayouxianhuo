<?php

class userModel extends model
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function getUserInfoByAppId($openId) {
        $where = [
            'where' => 'wxOpenId = ?',
            'param' => [$openId],
            'isRow' => true,
        ];
        return $this->select($where);
    }

    public function getUserInfoByToken($userToken) {
        $where = [
            'where' => 'userToken = ?',
            'param' => [$userToken],
            'isRow' => true,
        ];
        return $this->select($where);
    }

}

?>