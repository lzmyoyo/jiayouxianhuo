<?php

class userModel extends model
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function getUserInfoByAppId($appId) {
        $where = [
            'where' => 'openId = ?',
            'param' => [$appId],
            'isRow' => true,
        ];
        return $this->select($where);
    }

}

?>