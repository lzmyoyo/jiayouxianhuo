<?php
	class cookie {
		public static function setCookie($name = '', $value = '', $time = 0 , $path = '/' , $domain = '')
		{
			if ($name) {
				$nowTime = time();
				$exTime = $nowTime + $time;
				if(empty($domain)) {
					$serverName = $_SERVER['SERVER_NAME'];
					$serverNameArr = explode('.',$serverName);
					if(is_numeric(end($serverNameArr))) {
						$domain = '';
					} else {
						$serverNameArrLength = count($serverNameArr);
						$domain = $serverNameArr[$serverNameArrLength - 2].'.'.$serverNameArr[$serverNameArrLength - 1];
					}
				}
				$_COOKIE[$name] = $value;
				setcookie($name, $value, $exTime,$path,$domain);
			}
		}
		public static function getCookie($name) {
			$cookieValue = '';
			if ($name) {
				$cookieValue =  isset($_COOKIE[$name]) && $_COOKIE[$name] ? $_COOKIE[$name] : '';
			}
			return $cookieValue;
		}
	}
?>