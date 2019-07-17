<?php
	class start{

		public static function setIncludes(){
			$configArr = include LINPATH . 'core/include.php';
			$includePathArr = $configArr['includePath'];
			$includePathStr =  implode(PATH_SEPARATOR,$includePathArr).PATH_SEPARATOR.get_include_path();
			set_include_path($includePathStr);
		}

		public static function loadClass($className){
            include $className.'.php';
        }

        public static function run(){
        	try{
        		request::route();
        	}catch(Exception $e){
				echo $e->getMessage();
			}
        }

	}
?>