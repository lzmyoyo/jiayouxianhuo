<?php
class myException extends Exception{
	//生成异常日志文件。
	public function writeLogException(){
		$exceptionInfo = parent::getTrace();
		$exceptionMessage = parent::getMessage();
		$content = date('Y-m-d H:i:s')." 异常信息: ".$exceptionMessage."\r\n";
		$content .= "文件--->  ".$exceptionInfo[0]['file']."\r\n";			//产生异常的文件。
		$content .= "行号--->  ".$exceptionInfo[0]['line']."\r\n";			//文件的第几行
		$content .= "类名--->  ".$exceptionInfo[0]['class']."\r\n";		//类名称
		$content .= "方法名---> ".$exceptionInfo[0]['function']."\r\n方法参数--->";	//方法名称
		foreach($exceptionInfo[0]['args'] as $key=>$val){				//方法的所有参数
            $valStr = json_encode($val);
			$content .= "(".$key++."):".$valStr."   ";
		}
		$content .= "\r\n\r\n\r\n";
		FileUtil::appendContent(utils::config('log'),$content);
	}
	//打印异常信息
	public function echoException(){
		$exceptionInfo = parent::getTrace();
		$exceptionMessage = parent::getMessage();
		$content = date('Y-m-d H:i:s')."异常信息:".$exceptionMessage."<br/>";
		$content .= "文件--->".$exceptionInfo[0]['file']."<br/>";			//产生异常的文件。
		$content .= "行号--->".$exceptionInfo[0]['line']."<br/>";			//文件的第几行
		$content .= "类名--->".$exceptionInfo[0]['class']."<br/>";		//类名称
		$content .= "方法名--->".$exceptionInfo[0]['function']."<br/>方法参数--->";	//方法名称
		foreach($exceptionInfo[0]['args'] as $key=>$val){				//方法的所有参数
            $valStr = json_encode($val);
			$content .= "(".$key++."):".$valStr."   ";
		}
		echo $content;
	}
}
?>