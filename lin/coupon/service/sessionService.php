<?php

class sessionService
{
    public function setUserLogin($userId) {
        cookie::setCookie('userIsLogined',1,24*30*3600);
        $_SESSION['loginUser'] = $userId;
    }
    public function getUserLogin() {
        return isset($_SESSION['loginUser']) && $_SESSION['loginUser'] ? $_SESSION['loginUser'] : '';
    }

    public function setLoginMessageCode($messageCode) {
        $_SESSION['loginMessageCode'] = [
            'time' => time(),
            'messageCode' => $messageCode
        ];
    }
    public function getLoginMessageCode() {
        return isset($_SESSION['loginMessageCode']) ? $_SESSION['loginMessageCode'] : '';
    }
    public function setRegisterMessageCode($messageCode) {
        $_SESSION['registerMessageCode'] = [
            'time' => time(),
            'messageCode' => $messageCode
        ];
    }
    public function getRegisterMessageCode() {
        return isset($_SESSION['registerMessageCode']) ? $_SESSION['registerMessageCode'] : '';
    }


    public function checkMessageCode($messageCode, $messageType) {
        $messageType = strtoupper($messageType);
        $messageData = [];
        switch ($messageType) {
            case 'LOGIN':
                $messageData = $this->getLoginMessageCode($messageCode);
                break;
            case 'REGISTER':
                $messageData = $this->getRegisterMessageCode($messageCode);
            break;
        }

        if(empty($messageData)) {
            return false;
        }

        $nowTime = time();
        if(isset($messageData['time']) && $nowTime - $messageData['time'] > 1800) {
            return false;
        }

        if(isset($messageData['messageCode']) && $messageCode != $messageData['messageCode']) {
            return false;
        }
        return true;


    }


    public function setSessionValue($key, $val) {
        $_SESSION[$key] = $val;
        return true;
    }
    public function getSessionValue($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    }

}

?>