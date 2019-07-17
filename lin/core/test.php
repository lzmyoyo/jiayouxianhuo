<?php
	/*
	*	by linzhimin   2014-11-05 15
	*/
	header('Content-type: application/json');
	session_start();
	include 'pdoMysql.php';
	$dbConfig = array(
		'type' => 'mysql',
		'port' => 3306,
		'host' => '127.0.0.1',
		'dbName' => 'yii',
		'user' => 'root',
		'password' => 'xxxxx',
		'isLong' => true,
		'charset' => 'utf8'
	);
	//连接数据库
	$db = new pdoMysql($dbConfig);

	//初始化数据类
	$registrationObj = new registration();
	$registrationObj->db = $db;

	//获取数据
	
	$paramArr = array();

	$paramArr['page'] = getParam('page',0);
	$paramArr['pagesize'] = getParam('pagesize',20);
	

	//查询条件·
	$paramArr['email'] = getParam('email','');
	$paramArr['phone'] = getParam('phone','');
	$paramArr['name'] = getParam('name','');

	$paramArr['sort'] = getParam('sort','id');
	$paramArr['sorttype'] = getParam('sorttype',1);

	
	$cachKey = md5(implode(',',$paramArr));
	$nowTime = time();
	$registrationList = '';
	$isGetCache = false;
	//判断是否从session缓存中获取数据
	if(isset($_SESSION[$cachKey])){
		$cacheData = $_SESSION[$cachKey];
		if($nowTime - $cacheData['time'] <= 3*60){
			$registrationList = $cacheData['data'];
			$isGetCache = true;
		}
	}
	//缓存过期获取无缓存时数据库读取数据
	if(!$registrationList && !$isGetCache){
		$registrationList = $registrationObj->getRegistrationList($paramArr);
		$cacheArr = array(
			'key' => $cachKey,
			'time' => $nowTime,
			'data' => $registrationList
		);
		$_SESSION[$cachKey] = $cacheArr;
	}

	$resultJsonData = array();
	if($registrationList){
		$resultJsonData = array(
			'status' => 200,
			'message' => 'success',
			'list' => $registrationList['resultList'],
			'nums' => $registrationList['rowNums']
		);

	}else{
		if($registrationObj->errorMessage){
			$resultJsonData = array(
				'status' => 201,
				'message' => $registrationObj->errorMessage,
				'list' => '',
				'nums' => 0
			);
		}else{
			$resultJsonData = array(
				'status' => 202,
				'message' => 'not data',
				'list' => '',
				'nums' => 0
			);
		}
	}
	//返回json格式数据。
	echo json_encode($resultJsonData);


	/*
	* 获取GET请求参数
	* $stringName	参数键
	* $defaultName  当未获取到值时给定默认值
	*/
	function getParam($stringName,$defaultName=""){
        return isset($_GET[$stringName]) ? $_GET[$stringName] : $defaultName;
    }




	//数据类
	class registration{
		public $db;
		public $errorMessage;

		/*
		* 获取 registration数据，
		* $param 整体参数，一个数组，默认值为空数组
		* 参数完整值
		*	$param = array(
		*		'email' => '需要查询的email',
		*		‘phone’ => ‘需要查询的phone’,
		*		‘name’ => ‘需要查询的name’,
		*		‘page’ => ‘分页值’,
		*		‘pagesize’ => ‘分页值大小’,
		*		‘sort‘ => ‘排序值’,
		*		‘sorttype’ => '排序类型，1或者 2  ，1表示asc，2表示desc'
		*	);
		*/

		public function getRegistrationList($param=array()){
			$this->errorMessage = '';
			$tableParam = array(
				'table' => 'registration',
			);
			//查询条件拼接
			$whereStr = 'id > 0';
			$whereParam = array();
			
			if(!empty($param['email'])){
				$whereStr = 'email = ?';
				$whereParam[] = $param['email'];
			}
			if(!empty($param['phone'])){
				$whereStr .= ' and phone = ?';
				$whereParam[] = $param['phone'];
			}
			if(!empty($param['name'])){
				$whereStr .= ' and name = ?';
				$whereParam[] = $param['name'];
			}

			//where 条件
			if($whereStr){
				$tableParam['where'] = $whereStr;
				$tableParam['whereParam'] = $whereParam;
			}

			//是否分页获取
			if($param['page']){
				$tableParam['page'] = $param['page'];
				$tableParam['pageSize'] = $param['pagesize'];
			}

			//排序
			if($param['sort']){
				$sortAscDesc = ' asc';
				if($param['sorttype'] == 2){
					$sortAscDesc = ' desc';
				}
				$tableParam['order'] = $param['sort'].$sortAscDesc;
			}
			//数据获取
			try{
				$registrationList = $this->db->select($tableParam);
				return $registrationList;
			}catch(Exception $e){
				$this->errorMessage = $e->getMessage();
				return false;
			}

		}
	}


?>