<?php

class accessTokenModel extends model
{
    public function __construct()
    {
        parent::__construct(__CLASS__);
    }

    public function getToken() {
        $where = [
            'where' => 'id > 0',
            'param' => [],
            'isRow' => true,
        ];
        return $this->select($where);
    }

}

?>