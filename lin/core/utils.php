<?php

/**
 *  工具类
 */
	class utils{
		private static $db;

        public static $serviceNameArr = array();

        public static $serviceObjArr = array();
        /**
         * 访问配置文件内容。
         */
		public static function config($configName){
			$configNameArr = explode('.',$configName);
			if(count($configNameArr) == 1){
				$configArr = include APPPATH.'/config/config.php';
				$configItem = $configName;
			}else{
				$configArr = include APPPATH.'/config/'.$configNameArr[0].'.php';
				$configItem = $configNameArr[1];
			}
			return isset($configArr[$configItem]) ? $configArr[$configItem] : '';
		}

        /**
         * 调试打印数据至浏览器。并且退出。
         *
         */
		public static function pexit($data){
			echo '<pre>';
			print_r($data);
			exit;
		}

        /**
         * 判断一个控制器是否存在。
         */
		public static function controllerExists($controllerName){
			if(file_exists(APPPATH.'controllers/'.$controllerName.'.php')){
				return true;
			}else{
				return false;
			}
		}

        /**
         * 获取mysql连接类，返回连接对象
         */
		public static function getDB(){
        	$dbConfig = self::config('db');
        	if(!self::$db){
        		self::$db = new pdoMysql($dbConfig);
        	}
        	return self::$db;
        }

        /**
         * 根据参数生存url返回。
         */
        public static function getUrl($pathParam=''){
            $uri = '';
            if($pathParam){
                $uri = $pathParam;
            }
            return utils::config('siteUrl').'/'.$uri;
        }

        /**
         * 设置 session
         */
        public static function setSessionVal($name,$val){
            $_SESSION[$name] = $val;
            if(empty($val)){
                unset($_SESSION[$name]);
            }
        }

        /**
         *获取session。
         */
        public static function getSessionVal($name){
            if(isset($_SESSION[$name]) && $_SESSION[$name]){
                return $_SESSION[$name];
            }
        }
        /*
         * 返回json数据
         */
        public static function resposeJson($data){
            echo json_encode($data);
            exit;
        }

        public static function catchFile($fileName){
            return require APPPATH . 'cache/'.$fileName.'.php';
        }

        public static function getTableName($tableName){
            $tableName = strtolower($tableName);
            $dbConfig = utils::config('db');
            return $dbConfig['tableFlx'].$tableName;
        }

        public static function getSquarePoint($lng, $lat,$distance = 0.5){
            $EARTH_RADIUS = 6371;   //地球半径，平均半径为6371km
            $dlng =  2 * asin(sin($distance / (2 * $EARTH_RADIUS)) / cos(deg2rad($lat)));
            $dlng = rad2deg($dlng);
            //后面的是后来加上去得。百度地图高比长所以加上这点位置
            $dlat = 2 * asin(sin($distance / (2 * $EARTH_RADIUS)) / cos(deg2rad($lng)));
            $dlat = rad2deg($dlat)*2;
            return array('localhost' => array(
                array('lng'=>$lat + $dlat,'lat'=>$lng-$dlng),
                array('lng'=>$lat + $dlat, 'lat'=>$lng + $dlng),
                array('lng'=>$lat - $dlat, 'lat'=>$lng - $dlng),
                array('lng'=>$lat - $dlat, 'lat'=>$lng + $dlng)
            ),'home' =>
             array(
                'leftTop'=>array('lng'=>$lat + $dlat,'lat'=>$lng-$dlng),
                'rightTop'=>array('lng'=>$lat + $dlat, 'lat'=>$lng + $dlng),
                'leftBottom'=>array('lng'=>$lat - $dlat, 'lat'=>$lng - $dlng),
                'rightBottom'=>array('lng'=>$lat - $dlat, 'lat'=>$lng + $dlng)
            ));
        }



        public static function setCookie($name,$cookieVal,$time=7200){
            $_COOKIE[$name] = $cookieVal;
            setcookie($name,$cookieVal,time() + $time,'/');
        }

        public static function getCookie($name){
            if(isset($_COOKIE[$name])){
                return $_COOKIE[$name];
            }else{
                return false;
            }
        }


        public static function showOrderStatus($orderInfo,$userType){
            $orderService = utils::getService('order');
            $orderStatus = $orderService->orderStatus;
            $payStatus = $orderService->orderPayStatus;
            $resultOrderStatus = array();
            $nowTime = time();
            $resultOrderStatus['pstatus'] =  $orderStatus[$orderInfo['pstatus']];
            $resultOrderStatus['payStatus'] =  $payStatus[$orderInfo['pay_status']];

            switch($userType){
                case 1:
                    if($orderInfo['pstatus'] == 1 && $orderInfo['paddtime']+1800 <= $nowTime){
                        $resultOrderStatus['cancelOrder'] = '取消订单';
                    }
                    if(in_array($orderInfo['pstatus'],array(0,5,6)) && !$orderInfo['is_complain']){
                        if($orderInfo['update_time']+(3*24*3600) >= $nowTime){
                            $resultOrderStatus['complain'] = '投诉卖家';
                        }
                    }
                    if($orderInfo['pstatus'] == 0 && !$orderInfo['is_assess']){
                        if($orderInfo['update_time']+(3*24*3600) >= $nowTime){
                            $resultOrderStatus['assess'] = '评价卖家';
                        }
                    }
                break;
                case 2:
                    if(in_array($orderInfo['pstatus'],array(1,2,3,4))){
                        $resultOrderStatus['cancelOrder'] = '取消订单';
                    }
                    if($orderInfo['pstatus'] == 0){
                        $resultOrderStatus['complain'] = '投诉买家';
                        $resultOrderStatus['assess'] = '评价买家';
                    }
                    if($orderInfo['pay_type'] == 0 && $orderInfo['pstatus'] == 4 ){
                        $resultOrderStatus['turePrice'] = '已收到货款';
                    }
                break;
                default:
                    break;
            }
            return $resultOrderStatus;
        }
        public static function getService($serviceName){
            $serviceName .= 'Service';
            if(!in_array($serviceName,self::$serviceNameArr)){
                $allServiceName = self::$serviceNameArr;
                $allServiceName[] = $serviceName;
                $allServiceObj = self::$serviceObjArr;
                $serviceObj = new $serviceName();
                $allServiceObj[$serviceName] = $serviceObj;
            }else{
                $allServiceObj = self::$serviceObjArr;
            }
            return $allServiceObj[$serviceName];
        }

		public static function getLocalTime($timeSes) {
			$diffTime = cookie::getCookie('diffTime');
			$diffSesTime = (-8 - $diffTime) * 60 * 60;
			$localTime = $timeSes + $diffSesTime;
			return $localTime;
		}
		public static function generateUrl_un($str) {
			$uri = urlencode(preg_replace('/[\.|\/|\?|&|(|)|\/|\+|\\\|\'|"|,]+/', '-', strtolower(trim($str))));
			$uri = str_replace('+', '-', $uri);
			$uri = str_replace('%', '-', $uri);
			$uri = str_replace('__', '-', $uri);
			$uri = str_replace('-.', '-', $uri);
			$uri = preg_replace('/[-]+/', '-',$uri);
			return $uri;
		}

		public static function getSmsCode($lengthNum = 4) {
            $randSmsStr = '';
            for($i=1;$i<=$lengthNum;$i++) {
                $randSmsStr .= rand(0,9);
            }
            return $randSmsStr;
        }


        public static function mobileCheck($phoneNum)
        {
            $PHONE_NUMBER_REG = "/^(13[0-9]|14[579]|15[0-3,5-9]|16[6]|17[0135678]|18[0-9]|19[89])\\d{8}$/";
            $isMobileNum = preg_match_all($PHONE_NUMBER_REG,$phoneNum,$array);
            return $isMobileNum;
        }

        public static  function getUniqueValue() {
            return md5(uniqid(mt_rand(),true));
        }




        public static function sendApiRequest($url, $params = array(), $type = 'GET', $headers = array(), $timeout = 20,  $getHttpCode = false){
            $ch = curl_init();
            if($type == 'GET'){
                if($params){
                    foreach($params as $paramKey=>$paramVal){
                        $paramArr[] = $paramKey.'='.$paramVal;
                    }
                    $url = $url.'?'.implode('&',$paramArr);
                }
                $url = str_replace(' ', '%20', $url);
            }
            curl_setopt ($ch, CURLOPT_URL, $url);
            if($headers){
                curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_NOSIGNAL, true);               //注意，毫秒超时一定要设置这个
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*1000);   //超时时间200毫秒
            switch ($type){
                case "GET" :
                    curl_setopt($ch, CURLOPT_HTTPGET, true);
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POST,true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
                    break;
                case "PUT" :
                    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
                    break;
                case "DELETE":
                    curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
                    break;
            }
            $result = curl_exec($ch);
            if($getHttpCode) {
                $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            }
            curl_close($ch);
            if($getHttpCode) {
                return array(
                    'status' => $httpCode,
                    'data' => $result
                );
            } else {
                return $result;
            }
        }


	}
?>