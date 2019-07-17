<?php

    //所有service 方法如果需要调用数据库，都必须先调用 self::getSelfModel方法。切记。不管之前是否被调用过。

class linservice
{
    public $model;

    public function __construct($modelName){
        $modelName = $modelName.'Model';
        $this->model = new $modelName();
    }
    public function find($id){
        return $this->model->find($id);
    }
}
?>