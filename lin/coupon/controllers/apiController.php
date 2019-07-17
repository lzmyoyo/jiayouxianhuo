<?php

class apiController extends controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function setView($data = array(), $template = '')
    {
        parent::setView($data, $template);
    }
}

?>