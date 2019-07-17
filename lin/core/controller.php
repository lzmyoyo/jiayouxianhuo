<?php
	class controller{
        public $style = array();
        public $url;  //访问的url地址
        public $title;
        public $coustomData;
        public $defaultDataArr;
        public function __construct() {
            $cssArray = array();
            $jsArray = array('jquery-1.10.2.js');
            $this->style['css'] = $cssArray;
            $this->style['js'] = $jsArray;
            $this->url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

		public function __call($functionName,$args){
			throw new Exception("this is not function", 1);
		}

        public function setView($data=array(),$template=''){
            if($this->coustomData){
                foreach($this->coustomData as $dkey=>$dval){
                    $this->defaultDataArr[$dkey] = $dval;
                }
            }
            $this->defaultDataArr['style'] = $this->style;
            $this->defaultDataArr['title'] = $this->title;
            $this->defaultDataArr['url'] = base64_encode($this->url);

            $data = array_merge($this->defaultDataArr,$data);
            if(empty($template)){
                if(request::$newPath){
                    $template .= request::$newPath.'/';
                }
                $viewControll = str_replace('Controller','',request::$controllerName);
                $template .= $viewControll.'/'.request::$actionName;
            }
            try{
                view::display($data,$template);
            }catch (myException $e){
                $e->writeLogException();
            }
        }
        public function getRequestName(){
            return array(
                request::$controllerName,
                request::$actionName
            );
        }
        public function tip($title, $url = '', $timeout = 3) {
            if (!$url) {
                $url = utils::config('siteUrl');
            }
            $this->title = $title;
            $data = array(
                'timeout'=>$timeout,
                'backurl' => $url
            );
            $this->setView($data,'public/tip');
            exit;
        }

        public function reponseJsonData($data) {
            header('Content-Type:application/json; charset=utf-8');
            echo json_encode($data, true);
            exit;
        }

        public function reponseJsonDataNew($code, $data, $message) {
            header('Content-Type:application/json; charset=utf-8');
            $resultData = [
                'code' => $code,
                'data' => $data,
                'message' => $message
            ];
            echo json_encode($resultData, true);
            exit;
        }
	}
?>