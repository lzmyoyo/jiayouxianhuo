<?php
	class model{
        public $db;
        public $tableName;
        public function __construct($tableName){
            $dbConfig = utils::config('db');
            $tableName = str_replace('Model','',$tableName);
            $this->tableName = strtolower($dbConfig['tableFlx'].$tableName);
            if(!$this->db){
                $db = utils::getDB();
                $this->db = $db;
            }
        }

		public function getErrorDbMessage(){
			return $this->db->getErrorMessage();
		}

        public function getDB(){
            if(!$this->db){
                $db = utils::getDB();
                $this->db = $db;
            }
            return $this->db;
        }

        public function selectSql($sql,$param=array()){
            $resultList = $this->db->selectSql($sql,$param);
            return $resultList;
        }
        public function select($paramArr=array(),$isCount=false){
            $paramArr['table'] = $this->tableName;
            $resultList = $this->db->select($paramArr,$isCount);
            return $resultList;
        }
        public function insert($data){
			return $this->db->insert($this->tableName,$data);
        }
        public function save($data,$where='',$whereParam=array()){
            return $this->db->save($this->tableName,$data,$where,$whereParam);
        }
        public function delete($where='',$whereParam=array()){
            return $this->db->delete($this->tableName,$where,$whereParam);
        }
        public function find($id){
			if(isset(sqlDataCache::$sqlData[$this->tableName.$id])){
				$result = sqlDataCache::$sqlData[$this->tableName.$id];
			}else{
				$result = $this->db->find($this->tableName,$id);
				sqlDataCache::$sqlData[$this->tableName.$id] = $result;
			}
            return $result;
        }
        public function joinTable($mainTableName1,$tableName2,$joinField=array('id','id'),$fieldArr=array('*'),$joinType="inner join",$whereOrderLimit="",$param=array()){
            return $this->db->joinTable($mainTableName1,$tableName2,$joinField,$fieldArr,$joinType,$whereOrderLimit,$param);
        }

		public function selectIn($arrayIds,$fieldArr=array('*'),$orderBy=''){
            $arrayIds = array_unique($arrayIds);
			$idsStr = implode(',',$arrayIds);
			$inWhere = "id in({$idsStr})";
			$result = $this->db->querysSql($this->tableName,$fieldArr,$inWhere,$orderBy);
			$sortResultList = array();
			if(isset($result['resultList']) && $result['resultList']){
				$idKeyResult = array();
				foreach($result['resultList'] as $resultKey=>$resultInfo){
					$idKeyResult[$resultInfo['id']] = $resultInfo;
				}
				//进行排序后返回回去，in查询是没有排序的。
				foreach($arrayIds as $id) {
					$sortResultList[$id] = isset($idKeyResult[$id]) ? $idKeyResult[$id] : '';
				}
			}
			return $sortResultList;
		}

        public function querySql($tableName,$fieldArr=array('*'),$where='',$orderBy='',$groupBy='',$param=array())
        {
            return $this->db->querysSql($tableName,$fieldArr,$where,$orderBy,$groupBy,$param);
        }
        public function executeSql($sql){
            return $this->db->executeSql($sql);
        }
        public function beginTransaction(){
			$this->db->beginTransaction();
        }
        public function commit(){
			return $this->db->commit();
        }
        public function rollback(){
            $this->db->rollBack();
        }
	}
?>