<?php
	class request{
        public static $controllerName;
        public static $actionName;
        public static $newPath;
		public static $requestServer = array();
		public static $inputParam = array();

		public static function path(){
			$uri = $_SERVER['REQUEST_URI'];
			$uriArr = explode('?',$uri);
			$uri = $uriArr[0];
			if($uri!='/'){
				$uri = ltrim($uri,'/');
			}
			define('SERVERNAME_PATH',$uri);
			return $uri;
		}

		//获取$_SERVER常量
		public static function getRequestServer(){
			self::$requestServer = $_SERVER;
			return self::$requestServer;
		}

		//获取请求访问类型  get、post
		public static function getRequestType(){
			$serVerList = self::getRequestServer();
			return $serVerList['REQUEST_METHOD'];
		}


        // 路由访问。
		public static function route(){
			//访问路径
			$pathFileUrl = self::path();
			//静态路由列表
			$staticRouteArr = utils::config('routes.static');
			$staticRouteArrKey = array();
			if($staticRouteArr){
				$staticRouteArrKey = array_keys($staticRouteArr);
			}
			$isRouteUrl = false;
            $newPath = '';
			$type = 'get';
			$useAction = 'indexController@index';
            $groupName = '';
			$param = array();
			//判断是否为静态路由地址。

			if(in_array($pathFileUrl,$staticRouteArrKey)){
				if(isset($staticRouteArr[$pathFileUrl]['type']) && !empty($staticRouteArr[$pathFileUrl]['type'])){
					$type = $staticRouteArr[$pathFileUrl]['type'];
				}
                if(isset($staticRouteArr[$pathFileUrl]['group']) && !empty($staticRouteArr[$pathFileUrl]['group'])){
                    $groupName = $staticRouteArr[$pathFileUrl]['group'];
                }
				if(isset($staticRouteArr[$pathFileUrl]['uses']) && !empty($staticRouteArr[$pathFileUrl]['uses'])){
					$useAction = $staticRouteArr[$pathFileUrl]['uses'];
				}
				$isRouteUrl = true;
			}else{
				//判断是否为动态路由地址
				$changeRouteArrKey = array();
				$changeRouteArr = utils::config('routes.change');
				if($changeRouteArr){
					$changeRouteArrKey = array_keys($changeRouteArr);
					foreach($changeRouteArrKey as $cval){
						$cpath = $cval;
						//替换动态路由中定义的 可变 参数，以及特殊字符的替换。
						$cval = str_replace('{id}','(\d+)',$cval);
						$cval = str_replace('{string}','(.*)',$cval);
						$cval = str_replace('/','\/',$cval);
						$pregPath = '/^'.$cval.'$/i';
						$isHas = preg_match_all($pregPath, $pathFileUrl,$uriParam);
						if($isHas){
							$type = $changeRouteArr[$cpath]['type'];
                            if(isset($changeRouteArr[$cpath]['group'])){
                                $groupName = $changeRouteArr[$cpath]['group'];
                            }
							if(isset($changeRouteArr[$cpath]['uses'])){
								$useAction = $changeRouteArr[$cpath]['uses'];
							}
							foreach($uriParam as $ukey=>$uval){
								if($ukey == 0){
									continue;
								}else{
									if($uval[0] == '?'){
										break;
									}else{
										$param[] = $uval[0];
									}
								}
							}
					 		$isRouteUrl = true;
							break;
						}
					}
				}
			}
			//如果不是静态路由也不是动态路由，就按路径注册普通路由地址，此路由访问的先决条件是需要对应的 controller 和 action
			// 访问目录第一个是 控制器名称，第二个是控制器方法，滴第三个之后是方法中的参数。
            $isAdmin = false;
			if(!$isRouteUrl){
				if($pathFileUrl!='/'){
                    $pathConAcName = self::pathToController($pathFileUrl);
                    $useAction = $pathConAcName['controller'].'Controller@'.$pathConAcName['action'];
                    $param = $pathConAcName['param'];
                    $newPath = $pathConAcName['newPath'];
                    self::$newPath = $newPath;
				}
			}else{
                $newPath = $groupName;
                self::$newPath = $newPath;
            }

            //分割控制器和函数，得到控制器名称和函数名称
            $useController = explode('@', $useAction);
            $controllerName = $useController[0];
            if (isset($useController[1])) {
                $useControllerArr = explode('?',$useController[1]);
                $actionName = $useControllerArr[0];
            }else{
                $actionName = 'index';
            }

            self::$controllerName = $controllerName;
            self::$actionName = $actionName;
            define('CONTROLLERNAME',$controllerName);
            define('ACTIONNAME',$actionName);
            //实例化控制器并判断控制器是否带有参数。
            if(!$newPath){
                if(utils::controllerExists($controllerName)){
                    $controllerObj = new $controllerName();
                }else{
                    throw new myException('未找到控制器'.$controllerName.'，请检查文件。');
                }
            }else{
                $file = APPPATH.$newPath.'Controllers/'.$controllerName.'.php';
                if(file_exists($file)){
                    include $file;
                    $controllerObj = new $controllerName();

				}else{
                    throw new myException('未找到控制器器'.$file.'，请检查文件。');
                }
            }

            $hasParam = self::getMethodParam($controllerObj, $actionName);
            //调用控制器的前置方法
            if (method_exists($controllerObj, '_before')) {
                $controllerObj->_before();
            }
            //调用控制器对应的方法
            if ($param || $hasParam) {
                $controllerObj->$actionName($param);
            } else {
                $controllerObj->$actionName();
            }

            //调用控制器的后置方法
            if (method_exists($controllerObj, '_after')) {
                $controllerObj->_after();
            }

		}


        private static function pathToController($pathFileUrl){
            $controllerName = 'index';
            $actionName = 'index';
            $param = array();
            $sitePathArr = explode('/',$pathFileUrl);

            $newPath = '';
            if($sitePathArr) {
                foreach ($sitePathArr as $key => $val) {
                    switch($key){
                        case 0:
                           //$val = self::replaceName($val);
                           if(utils::config('newPath') && in_array($val,utils::config('newPath'))){
                               $newPath = $val;
                           }else{
                               $controllerName = $val;
                           }
                        break;
                        case 1:
                            //$val = self::replaceName($val);
							if($val){
								if($newPath){
									$controllerName = $val;
								} else {
									$actionName = $val;
								}
							}
                        break;
                        case 2:
                            if($newPath){
                                //$val = self::replaceName($val);
                                $actionName = $val;
                            }else{
                                $valArr = explode('?',$val);
                                $param[] = $valArr[0];
                            }
                        break;
                        default:
                            $valArr = explode('?',$val);
                            $param[] = $valArr[0];
                        break;
                    }
                }
            }

            return array(
                'controller' => $controllerName,
                'action' => $actionName,
                'newPath' => $newPath,
                'param' => $param
            );
        }
        private static function replaceName($name){
            $lowname = strtolower($name);
            $nameArr = explode('-', $lowname);
            if(count($nameArr)>1){
                foreach ($nameArr as $nkey => $nval) {
                    if ($nkey != 0) {
                        $name .= ucfirst($nval);
                    } else {
                        $name = $nval;
                    }
                }
            }
            return $name;
        }

		public static function getMethodParam($class,$method){
			$reflection = new ReflectionClass($class);
			$parameters = $reflection->getMethod($method)->getParameters();
			/*$info = array();
			foreach($parameters as $key=>$param) {
				$info[$key]['name'] = $param->getName();//获取方法的参数
				$info[$key]['value'] = ($param->isDefaultValueAvailable()) ? $param->getDefaultValue() : '';//获取默认值
			}*/
			return $parameters;
		}

        public static function getParam($name,$defaultVal=''){
            return isset($_GET[$name]) ? $_GET[$name] : $defaultVal;
        }

        public static function postParam($name,$defaultVal=''){
            return isset($_POST[$name]) ? $_POST[$name] : $defaultVal;
        }

        public static function jumpHeader($param=array()){
            $headerCode = isset($param['code']) ? $param['code'] : 302;
            $url = isset($param['url']) ? $param['url'] : '';
            switch($headerCode){
                case 302:
                    if($url){
                        header('Location: '.$url);
                    }else{
                        $exception = new myException('跳转地址未设置,请加入参数url');
                        $exception->echoException();
                    }
                break;
                case 301:
                    if($url){
                        header('HTTP/1.1 301 Moved Permanently');
                        header('Location: '.$url);
                    }else{
                        $exception = new myException('跳转地址未设置,请加入参数url');
                        $exception->echoException();
                    }
                break;
                case 404:
                    header('HTTP/1.1 404 Not Found');
                break;
            }
            exit;
        }

        public static function sendApiRequest($url, $params = array(), $type = 'GET', $headers = array(), $timeout = 20, $resultArr = true){
            if($type == 'POSTSTRING'){
                $params = array(json_encode($params));
            }
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
                case "POSTBUILD":
                    curl_setopt($ch, CURLOPT_POST,true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
                    break;
                case "POSTSTRING":
                    curl_setopt($ch, CURLOPT_POST,true);
                    $postDataStr = implode('&',$params);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$postDataStr);
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
            FileUtil::appendContent('api.txt','请求接口:'.$url);
            FileUtil::appendContent('api.txt',"\r\n返回值:".$result);
            curl_close($ch);
            if($resultArr) {
                if($result) {
                    $resultData = json_decode($result,true);
                    return $resultData;
                } else {
                    return [];
                }
            } else {
                return $result;
            }
        }

        public static function getInputParam($paramName,$defaultVal = '') {
		    self::requestInputParam();
		    $inputParam = self::$inputParam;
		    return isset($inputParam[$paramName]) && $inputParam[$paramName] ? $inputParam[$paramName] : $defaultVal;
        }
        public static function requestInputParam() {
		    if(empty(self::$inputParam)) {
                $paramJson = file_get_contents('php://input', 'r');
                if($paramJson) {
                    self::$inputParam = json_decode($paramJson,true);
                    return self::$inputParam;
                }
            } else {
                return self::$inputParam;
            }
        }


	}
?>