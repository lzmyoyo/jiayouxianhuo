<?php
class FileUtil{
	private static $arr;
	private static $i=0;	
	/*
	 * 打开文件返回文件句柄。一共有三个参数。
	 */
	public static function fileOpen(){
		self::$arr=func_get_args();
		self::$i=func_num_args();
		$returnResult;
		switch(self::$i){
			case 1:
				$returnResult = self::fileOpen1(self::$arr[0]);
			break;
			case 2:
				$returnResult = self::fileOpen2(self::$arr[0],self::$arr[1]);
			break;
			case 3:
				$returnResult = self::fileOpen3(self::$arr[0],self::$arr[1],self::$arr[2]);
			break;
			default:
				echo "必须传入一个或两个参数";
				exit;
			break;
		}
		return $returnResult;
	}
	
	/*
	 * 获取扩展名
	 * $file  文件名
	*/
	
	public static function get_extension($file){		
		$ext = substr(strrchr($file, '.'), 1);	
		return $ext;
	}
	
	
	
	
	/*
	 * 打开文件，一个参数
	 * 1.$file		文件地址。绝对路径的地址，以a+模式打开
	 * 返回文件句柄。
	 */
	public static function fileOpen1($file){
		if(!self::checkFileDirExists($file)){
			echo "请检查目录 $dir 是否存在!";
			exit;
		}
		$fp = fopen($file, "a+");
		return $fp;
	}
	/*
	 * 打开文件，二个参数
	 * 1.$file		文件地址。
	 * 2.$mode		打开文件方式  r  w   a
	 * 返回文件句柄。
	 */
	public static function fileOpen2($file,$mode){
		if(!self::checkFileDirExists($file)){
			echo "请检查目录 $dir 是否存在!";
			exit;
		}
		$fp = @fopen($file,$mode);
		return $fp;
	}
	/*
	 * 打开文件，三个参数
	 * 1.$file		文件地址。
	 * 2.$mode		打开文件方式  r  w   a
	 * 3.$isCreateDir	文件路径不存在的时候是否创建路径
	 * 返回文件句柄。
	 */
	public static function fileOpen3($file,$mode,$isCreateDir){
		if(!self::checkFileDirExists($file)){
			$dir = dirname($file);
			self::createDir($dir);
		}
		$fp = fopen($file,$mode);
		return $fp;
	}
	/*
	 * 创建目录
	 */
	public static function createDir($dirs){
		//先判断目录是否存在
		if(file_exists($dirs)){
			return true;
		}
        $domain = strstr($dirs,':');
        if($domain){
		  $dir = substr($dirs,0,2);
		  $dir_str = substr($dirs,3);
        }else{
            $dir = '';
            $dir_str = $dirs;
        }
		$dir_arr = explode('/', $dir_str);
		if($dir_arr){
			foreach($dir_arr as $val){
				//判断文件夹是否存在 
				$dir.='/'.$val;
				if (!file_exists($dir)){ 
					if(!mkdir($dir,0777,true)){
						return false;
					}
				}
			}
		}
		return true;
	}
	
	
	/*function createDir($dir){
         if (is_dir($dir)){  //判断目录存在否，存在不创建
             return true;
         }else{ //不存在创建
             $re=mkdir($dir,0777,true); //第三个参数为true即可以创建多极目录
             if($re){
                 return true;
             }else{
                 return false;
             }
         }
     }*/
	
	
	
	/*
	 * 检查文件目录是否存在
	 */
	public static function checkFileDirExists($file){
		$dir = dirname($file);   //返回路径的目录部分dirname()
		if(!file_exists($dir)){   //判断目录是否存在
			return false;
		}else{
			return true;
		}
	}
	/*
	 * 读取文件成为一个数组。每行为数组的一个元素
	 */
	public static function getFileToArray($file){
		if(!file_exists($file)){
			return false;
		}
		$arrayFile = file($file);
		return $arrayFile;
	}
	
	//这里filechar是指读取每行中多少个字节
	public static function getFileLine($file,$filechar){
		if(!file_exists($file)){
			return false;
		}
		$content ='';
		$fp = self::fileOpen($file,'r');
		while(!feof($fp)){
			$content.=fgets($fp,$filechar);
		}
		fclose($fp);
		return $content;
	}
	//过滤html和php标签的读取方式。$targ可以用来表示保留哪些标签，例子"<body><p>",要是不传递这个参数。就过滤掉所有的html和php标签
	public static function getFileTarg($file,$filechar,$targ=null){
		if(!file_exists($file)){
			return false;
		}
		$content ='';
		$fp = self::fileOpen($file,'r');
		while(!feof($fp)){
			$content.=fgetss($fp,$filechar,$targ);
		}
		fclose($fp);
		return $content;
	}
	public static function getFileChar($file){
		if(!file_exists($file)){
			return false;
		}
		$content ='';
		$fp = self::fileOpen($file,'r');
		while(!feof($fp)){
			$content .=fgetc($fp);
		}
		fclose($fp);
		return $content;
	}
	//这里filechar是指读取每行中多少个字节
	public static function getFileRead($file,$filechar){
		if(!file_exists($file)){
			return false;
		}
		$content ='';
		$fp = self::fileOpen($file,'r');
		while(!feof($fp)){
			$content.= fread($fp,$filechar);
		}
		fclose($fp);
		return $content;
	}
	//根据预定义格式读取文件。返回一个数组
	public static function getFileScanf($file,$type){
		if(!file_exists($file)){
			return false;
		}
		$content =array();
		$fp = self::fileOpen($file,'r');
		while($user = fscanf($fp,$type)){
			$content[] =$user;
		}
		fclose($fp);
		return $content;
	}
	//读取cvs格式文件,$filechar表示一行读取多少个字节,$type是指cvs文件的分割符
	public static function getFileCsv($file,$filechar,$type=","){
		if(!file_exists($file)){
			return false;
		}
		$fp = self::fileOpen($file,'r');
		$content = array();
		while ($filecontent = fgetcsv($fp,$filechar,$type)){
			$content[] = $filecontent;
		}
		fclose($fp);
		return $content;
	}
	//读取ini文件,这里并不是说一定要ini文件扩展名的文件。也可以使txt。只是ini文件格式那种就可以
	public static function getFileIni($file,$type=true){
		if(!file_exists($file)){
			return false;
		}
		$fileContent = parse_ini_file($file,$type);
		return $fileContent;
	}
	
	//往文件写入,$type 是表示当目录不存在的是否是否创建目录
	public static function writeFileContent($file,$content,$type=true){
		$dir = dirname($file);
		if(!file_exists($dir)){
			if($type){
				self::createDir($dir);
			}else{
				return false;
			}
		}
		$fp = self::fileOpen($file,'w');
		fwrite($fp, $content);
		@fclose($fp);
		return true;
	}
	//往文件追加内容。
	public static function appendContent($file,$content){
		if(!file_exists($file)){
			self::writeFileContent($file,$content);
			return true;
		}
		$fp = self::fileOpen($file,'a');
		fwrite($fp,$content);
		fclose($fp);
		return true;
	}
	
	//打开目录句柄。返回目录句柄, $type是表示要是目录不存在是否创建目录
	public static function openDir($dir,$type=true){
		//。目录不存在,是否创建目录
		if($type){
			self::createDir($dir);
		}
		if(file_exists($dir)){
			$dirFp = opendir($dir);
			return $dirFp;
		}else{
			return false;
		}
	}
	//返回目录下所有文件和目录,返回的是一个数组，$order用于表示排序,值可以是1表示倒序排列
	public static function getDirdir($dir,$order=null){
		if(file_exists($dir)){
			return scandir($dir,$order);
		}else{
			return false;
		}
	}
	//删除文件
	public static function deleteFile($file){
		if(file_exists($file)){
			@unlink($file);
		}
		return true;
	}
	//删除目录
	public static function deleteDir($dir){
		$dirFp = self::openDir($dir,false);
		if($dirFp){
			while(($file = readdir($dirFp))!=false){
				if(($file==".") || ($file=="..")) continue;
				if(is_dir($dir.'/'.$file)){
					self::deleteDir($dir.'/'.$file);
				}else{
					self::deleteFile($dir.'/'.$file);
				}
			}
			closedir($dirFp);
			rmdir($dir);
		}
		return true;
	}
	//获取文件的信息。
	public static function getFileInfo($file){
		$fileArr = pathinfo($file);
		if(!file_exists($file)){
			throw new myException("文件不存在",1);
		}else{
			$fileArr['size'] = filesize($file);
		}
		return $fileArr;
	}
	//获取网络地址内容
	public static function httpGetContent($url){
		$content = file_get_contents($url);
		return $content;
	}
	
	public function request($url,$postfields,$cookie_jar,$referer){ 
		$ch = curl_init();
		//curl_setopt($c, CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)');
		$options = array(CURLOPT_URL => $url, 
			  CURLOPT_HEADER => 0, 
			  CURLOPT_NOBODY => 0, 
			  CURLOPT_PORT => 80, 
			  CURLOPT_POST => 1, 
			  CURLOPT_POSTFIELDS => $postfields, 
			  CURLOPT_RETURNTRANSFER => 1, 
			  CURLOPT_FOLLOWLOCATION => 1, 
			  CURLOPT_COOKIEJAR => $cookie_jar, 
			  CURLOPT_COOKIEFILE => $cookie_jar, 
			  CURLOPT_REFERER => $referer 
		); 
		curl_setopt_array($ch, $options); 
		$code = curl_exec($ch); 
		curl_close($ch); 
		return $code; 
	}
	//
	public static function httpCurlGetContent($url,$data=''){
		$cookie_jar =tempnam(DOCUMDNG_ROOT."\temp","cookie");
		$content = self::request($url, $data, $cookie_jar, '');
		return $content;
	}
	
	
	
	
	public static function vita_get_url_content($url) {
		if(function_exists('file_get_contents')) {
			$file_contents = file_get_contents($url);
		} else {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, curlopt_url, $url);
			curl_setopt ($ch, curlopt_returntransfer, 1);
			curl_setopt ($ch, curlopt_connecttimeout, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		return $file_contents;
	}
	
	
	
	public static function get_curl($url){
		$ch = curl_init();
		$timeout = 10;    //设置缓冲的时间
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$handles = curl_exec($ch);
		curl_close($ch);
		return $handles;
	}
	
	function curl_file_get_contents($url){
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	   curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	   curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   $r = curl_exec($ch);
	   curl_close($ch);
	   return $r;
	 }
   public static function closeFile(){
        
   }
}
?>