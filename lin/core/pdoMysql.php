<?php
	/*
	*	by linzhimin   2014-11-05 15
	*/
	class pdoMysql extends PDO{

		private $errorMessage = array();

		private $connectionStatus = false;

		/**
		* 构造函数，初始化的时候就连接数据库。
		**/
		public function __construct($config){
			if(!$this->connectionStatus){
				$this->Connection($config);
			}
		}

		/**
		* 获取错误信息
		**/
		public function getErrorMessage(){
			return $this->errorMessage;
		}

		/**
		* 获取连接状态
		**/
		public function getConnectionStatus(){
			return $this->connectionStatus;
		}

		/**
		* 连接数据库的方法
		* 参数 $config
		* $config = array(
			* 'type' => 'mysql',
			* 'port' => 3306,
			* 'host' => '127.0.0.1',
			* 'dbName' => 'yii',
			* 'user' => 'root',
			* 'password' => 'xxxxxx',
			* 'isLong' => true,
			* 'charset' => 'utf8'
		* );
		**/
		public function connection($config){

			$this->errorMessage = array();

			$type = isset($config['type']) && !empty($config['type']) ? $config['type'] : 'mysql';
			
			$port = isset($config['port']) && !empty($config['port']) ? $config['port'] : '';

			$host = isset($config['host']) && !empty($config['host']) ? $config['host'] : '127.0.0.1';

			$dbName = isset($config['dbName']) && !empty($config['dbName']) ? $config['dbName'] : '';
            if($port){
			    $host .= ":$port";
            }
			$isLong = '';
			if(isset($config['isLong']) && $config['isLong']){
				$isLong = array(parent::ATTR_PERSISTENT => true);
			}

			if(!$dbName){
				$this->errorMessage[] = '数据库名称错误。';
			}
			if(!isset($config['user']) || empty($config['user'])){
				$this->errorMessage[] = '数据库用户名错误。';
			}
			if(!isset($config['password'])){
				$this->errorMessage[] = '数据库密码错误。';
			}
			$connectionDsn = "$type:host=$host;dbname=$dbName";
			if($this->errorMessage){
				throw new Exception("配置文件错误", 1);
			}else{
				try{
					if($isLong){
						parent::__construct($connectionDsn, $config['user'], $config['password'],$isLong);						
					}else{
						parent::__construct($connectionDsn, $config['user'], $config['password']);
					}
					if($config['charset']){
						parent::query('set names '.$config['charset']);
					}
					$this->errorMessage = array();
					$this->connectionStatus = true;
				}catch (PDOException $e) {
					$this->errorMessage[] = $e->getMessage();
					throw new Exception('无法连接到数据库'.$host.',错误原因：'.$e->getMessage(), 1);
				}
			}
		}



		/**
		* 介绍类信息。
		**/
		public function __toString(){
			return 'pdo 数据库操作类,数据库连接状态：'.$this->connectionStatus;
		}

		/**
		*析构函数，设置连接状态为关闭，并且清除错误信息
		**/

		public function __destruct(){
			$this->connectionStatus = false;
			$this->errorMessage = array();
		}


		/**
		* sql 语句查询，
		* 参数介绍
		* $sql 需要执行的sql语句，如: select * from user where id = ? , ?为占位符，简单的查询可以使用。复杂的查询占位符会失效。如 in(?)
		* $param 占位符对应的参数，多少个占位符对ing多少个数组值
		* 能使用占位符请尽量使用。
		**/

		public function selectSql($sql,$param=array()){
			FileUtil::appendContent('sqlData.txt',$sql."\r\n");
            try{
                $sth = $this->getSth($sql,$param);
                $rowNum = $sth->rowCount();
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                if($rowNum){
					$returnResult = array(
                        'rowNums' => $rowNum,
                        'resultList' => $result
                    );
					return $returnResult;
                }
                return false;
            }catch (Exception $e){
                throw new Exception($e->getMessage(), 1);
            }
		}

        //获取一行数据
        public function selectRow($sql,$param=array()){
			FileUtil::appendContent('sqlData.txt',$sql."\r\n");
            try{
                $sth = $this->getSth($sql,$param);
                $result = $sth->fetch(PDO::FETCH_ASSOC);
                if($result){
                    return $result;
                }
                return false;
            }catch (Exception $e){
                throw new Exception($e->getMessage(), 1);
            }
        }

        public function getSth($sql,$param=array()){
            $sth = $this->prepare($sql);
            if($param){
                $sth->execute($param);
            }else{
                $sth->execute();
            }
            $errorInfo = $sth->errorInfo();

            if($errorInfo[2]){
                $this->errorMessage[] = $sql;
                $this->errorMessage[] = $errorInfo[2];
                throw new Exception($errorInfo[2], 1);
                return false;
            }
            return $sth;
        }


		/**
		* 查询数据。
		**/
		public function select($paramArr,$isCount = false){
			$tableName = $paramArr['table'];
			if(isset($paramArr['field'])){
				$fieldArr = $paramArr['field'];
			}else{
				$fieldArr = array('*');
			}
			$fieldStr = implode(',',$fieldArr);
			$whereStr = '';
            $getRow = false;
            if(isset($paramArr['isRow']) && $paramArr['isRow']){
                $getRow = true;
                $paramArr['page'] = 1;
                $paramArr['pageSize'] = 1;

            }
			$bindParam = array();
			if(isset($paramArr['where'])){
				$whereStr = ' where '.$paramArr['where'];
				$bindParam = $paramArr['param'];
			}
			$orderByStr = $groupByStr = $limitStr = '';

            if(isset($paramArr['group'])){
				$groupByStr = ' group by '.$paramArr['group'];
			}
            $resultList = array();
            //判断只是获取总数
            if(!$isCount) {
                if (isset($paramArr['order'])) {
                    $orderByStr = ' order by ' . $paramArr['order'];
                }
                if (isset($paramArr['page']) && $paramArr['page'] && $paramArr['pageSize']) {
                    $paramArr['pageSize'] = intval($paramArr['pageSize']);
                    $paramArr['page'] = intval($paramArr['page']);
                    if (!$paramArr['page']) {
                        $paramArr['page'] = 1;
                    }
                    $limitStr = ' limit ' . ($paramArr['page'] * $paramArr['pageSize'] - $paramArr['pageSize']) . ',' . $paramArr['pageSize'];
                }
                if($groupByStr) {
                    $fieldStr.= ',count(*) as groupCountNums';
                }
                $sql = "select $fieldStr from $tableName" . $whereStr . $groupByStr . $orderByStr . $limitStr;

                $resultList = $this->selectSql($sql, $bindParam);
            }
            //是否需要返回总数
            if((isset($paramArr['isCount']) && $paramArr['isCount']) || $isCount && !$getRow){
                $sqlCount = 'select count(id) as nums from '.$tableName.$whereStr.$groupByStr;
                $countResult = $this->selectRow($sqlCount,$bindParam);
                $countNum = 0;
                if($countResult){
                    $countNum = $countResult['nums'];
                }
                $resultList['count'] = $countNum;
            }
            if(!$resultList){
                return false;
            }

            if($getRow){
                return $resultList['resultList'][0];
            }else{
                return $resultList;
            }
		}



        public function executeSql($sql){
			FileUtil::appendContent('sqlData.txt',$sql."\r\n");
            $sth = $this->prepare($sql);
            $sth->execute();
            $errorInfo = $sth->errorInfo();
            if($errorInfo[2]){
                $this->errorMessage[] = $sql;
                $this->errorMessage[] = $errorInfo[2];
                throw new Exception($errorInfo[2], 1);
                return false;
            }else{
                return true;
            }
        }


		/**
		* insert 
		* $table 表名称
		* $data 增加的数据键值对
		**/

		public function insert($table,$data){
			$fieldArr = $placeholderArr = $valArr = array();
			foreach($data as $field=>$val){
				$fieldArr[] = $field;
				$placeholderArr[] = '?';
				$valArr[] = $val;
			}
			$fieldStr = implode(',',$fieldArr);
			$placeholderStr = implode(',',$placeholderArr);
			$sql = "insert into $table ($fieldStr) values ($placeholderStr)";
			$sth = $this->prepare($sql);
			$sth->execute($valArr);
			$errorInfo = $sth->errorInfo();
			if($errorInfo[2]){
				$this->errorMessage[] = $sql;
				$this->errorMessage[] = $errorInfo[2];
				throw new myException($errorInfo[2], 1);
				return false;
			}else{
				$id = $this->lastInsertId();
				return $id;
			}
		}

		public function save($table,$data,$where='',$whereParam=array()){
			$whereStr = '';
			$newWhereParam = array();
			if($where){
				$whereStr = ' where '.$where;
			}
			$setStr = '';
			foreach($data as $field=>$val){
			    if($field == 'id') {
			        continue;
                }
				$setStr .= " $field = ?,";
				$newWhereParam[] = $val;
			}
			$setStr = substr($setStr,0,-1);
			if(isset($data['id']) && $data['id']){
				$whereStr = ' where id =?';
				$newWhereParam[] = $data['id'];
			}else{
				$newWhereParam = array_merge($newWhereParam,$whereParam);
			}
			$sql = "update $table set ".$setStr.$whereStr;
			$sth = $this->prepare($sql);
			$sth->execute($newWhereParam);
			$errorInfo = $sth->errorInfo();
			if($errorInfo[2]){
				$this->errorMessage[] = $sql;
				$this->errorMessage[] = $errorInfo[2];
				throw new Exception($errorInfo[2], 1);
				return false;
			}else{
				return $rowNum = $sth->rowCount();
			}
		}

        public function delete($table,$where='',$whereParam=array()){
            $whereStr = '';
            $newWhereParam = $whereParam;
            if($where){
                $whereStr = ' where '.$where;
            }
            $sql = "delete from $table ".$whereStr;
            $sth = $this->prepare($sql);
            $sth->execute($newWhereParam);
            $errorInfo = $sth->errorInfo();
            if($errorInfo[2]){
                $this->errorMessage[] = $sql;
                $this->errorMessage[] = $errorInfo[2];
                throw new Exception($errorInfo[2], 1);
                return false;
            }else{
                return $rowNum = $sth->rowCount();
            }
        }

        public function find($table,$id){
            $sql = 'select * from '.$table.' where id = ?';
            $sqlParam = array(
                $id
            );
            return $this->selectRow($sql,$sqlParam);
        }

        /**
         * 简单链表查询，
         * $tableName1 主表名称
         * $tableName2 连接表名称
         * $joinField  两个表之间的关联性 如 array('id','product_id')
         * $fieldArr   需要查询的字段， 需要注意，主表使用别名a代替， 关联表使用别名b代替。
         * $joinType   链接类型  默认 “inner join”
         */
        public function joinTable($tableName1,$tableName2,$joinField=array('id','id'),$fieldArr=array('*'),$joinType="inner join",$whereOrderLimit="",$param=array()){
            $allFieldStr = implode(',',$fieldArr);
            $sql = "select $allFieldStr from $tableName1 a $joinType $tableName2 b on a.$joinField[0] = b.$joinField[1]";
            if($whereOrderLimit){
                $sql .= " where $whereOrderLimit";
            }
            $result = $this->selectSql($sql,$param);
            return $result;
        }


        public function querysSql($tableName,$fieldArr=array('*'),$where='',$orderBy='',$groupBy='',$param=array()){
            $fieldStr = implode(',',$fieldArr);
            $sql = "select $fieldStr from $tableName";
            if($where){
                $sql .= " where $where";
            }
            if($groupBy){
                $sql .= " group by $groupBy";
            }
            if($orderBy){
                $sql .= " order by $orderBy";
            }
            return $this->selectSql($sql,$param);
        }

	}
?>