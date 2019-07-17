<?php
	class ImageUtil{
		public static $textContent;							//水印内容
		public static $watermarkFont;						//水印字体
		public static $watermarkImg;						//水印图片地址
		public static $fontSize=20;							//水印字体大小
		public static $watermarkImgSize=200;				//小于该宽度的图片不加水印
		public static $angle = 0;							//水印时偏转角度
		public static $watermarkOptionType = "bottomRight";	//水印位置
		public static $watermarkClarity = 60;				//水印的透明度100为最大值。不透明。  0为最小值。完全透明

		/*
		 * 这个是生成等比例图
		 * 三个参数。
		 * 1.imgFile    	图片地址。绝对地址
		 * 2.$newImgExt		生成的图片带有的后缀
		 * 3.$newImgSize	新图片的大小。
		 * 4.$newPath		新图片保存地址。绝对地址。最后不带斜杠  如E:/item  、该值不传的话就与存放位置就和原图同一目录
		 * 5.$minSize		小于这个大小的都不生成图片。
		 * 6.$type			已哪边为准进行生成图片。auto  自动判断。  width  以宽度为准。 height  以高度为准
		 * 7.$watermarkType	文字水印还是图片水印  text  or  image
		 * 8.$watermarkOption	水印的位置topLeft   topRight    center     bottomLeft     bottomRight
		 */
		public static function createSmallImg($imgFile,$newImgExt,$newImgSize,$newPath="",$minSize=200,$type="auto",$watermarkType="none",$watermarkOption=""){
			if(!file_exists($imgFile)){
				return array('status'=>false,'fileName'=>'');
			}
			$fileInfo = FileUtil::getFileInfo($imgFile);
			//图片名称。不带后缀的
			$imgName = $fileInfo["filename"];
			//图片后缀
			$imgExt = $fileInfo["extension"];
			//获取原图片的大小.
			$oldImgInfo = getimagesize($imgFile);
			$oldImgWidth = $oldImgInfo[0];
			$oldImgHeight = $oldImgInfo[1];

			if($type=="auto"){
				if($oldImgWidth>$oldImgHeight){
					if($oldImgWidth <= $minSize){
						//返回原图片的名称a.jpg
						return array('status'=>false,'fileName'=>$fileInfo["basename"]);
					}
					$newFileWidthHeight= self::createImgWidth($oldImgWidth,$oldImgHeight,$newImgSize);

				}else{
					if($oldImgHeight <= $minSize){
						return array('status'=>false,'fileName'=>$fileInfo["basename"]);
					}
					$newFileWidthHeight= self::createImgHeight($oldImgWidth,$oldImgHeight,$newImgSize);
				}
			}elseif($type=="width"){
				if($oldImgWidth <= $minSize){
					return array('status'=>false,'fileName'=>$fileInfo["basename"]);
				}
				$newFileWidthHeight= self::createImgWidth($oldImgWidth,$oldImgHeight,$newImgSize);
			}elseif($type=="height"){
				if($oldImgHeight <= $minSize){
					return array('status'=>false,'fileName'=>$fileInfo["basename"]);
				}
				$newFileWidthHeight= self::createImgHeight($oldImgWidth,$oldImgHeight,$newImgSize);
			}
			$newImgWidth = $newFileWidthHeight["width"];
			$newImgHeight = $newFileWidthHeight["height"];
			
			$dstImg = imagecreatetruecolor($newImgWidth,$newImgHeight);
			
			$srcImg=self::imgCreateFromExt($oldImgInfo[2],$imgFile);
			
			//保证png图片的透明色
			if($imgExt=="png"){
				imagealphablending($dstImg,false);
				imagesavealpha($dstImg,true);
			}
			//imagecopyresize
            //imagecopyresampled
			//无损压缩的方法
            imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newImgWidth, $newImgHeight,$oldImgWidth, $oldImgHeight);
			$newFileName = $imgName.$newImgExt.".".$imgExt;
			if(!$newPath){
				$newPath = $fileInfo['dirname'];
			}
			$smallImgName = $newPath."/".$newFileName;
			
			self::imageToExt($oldImgInfo[2],$dstImg,$smallImgName);
			
			
			if($watermarkType=="text"){
				self::createTextWatermark($smallImgName,$watermarkOption);
			}
			if($watermarkType=="image"){
				self::createImgWatermark($smallImgName,$watermarkOption);
			}
			imagedestroy($srcImg); 
			//返回新图片的名称a_s.jpg
			return array('status'=>true,'fileName'=>$newFileName);
		}
		
		
		/*
		 * 图片剪切
		 */
		public static function jianqieImage($filePath,$fileName,$fileExt='_w',$newImgWidth,$newImgHeight,$type='width'){
			$saveFileName='';
			if($type=='width'){
				$newFileSize = $newImgWidth;
			}
			if($type=='height'){
				$newFileSize = $newImgHeight;
			}
			$saveFileName = self::createSmallImg($filePath.$fileName, $fileExt, $newFileSize,"",1,$type);
			if(!$saveFileName['status']){
				$fileInfo = FileUtil::getFileInfo($filePath.$fileName);
				//图片名称。不带后缀的
				$saveFileName = $fileInfo["filename"].$fileExt.'.'.$fileInfo["extension"];
				$newFile = $filePath.$fileName;
			}else{
				$newFile = $filePath.$saveFileName['fileName'];
				$saveFileName = $saveFileName['fileName'];
			}
			$oldImgInfo = getimagesize($newFile);
			$oldImgWidth = $oldImgInfo[0];
			$oldImgHeight = $oldImgInfo[1];

			if($type=='height'){
				$x = ($oldImgWidth-$newImgWidth)/2;
				$y = 0;
			}else{
				$y = ($oldImgHeight-$newImgHeight)/2;
				$x = 0;
			}			
			$dstImg = imagecreatetruecolor($newImgWidth,$newImgHeight);			
			$srcImg=self::imgCreateFromExt($oldImgInfo[2],$newFile);

            imagecopy($dstImg, $srcImg, 0,0,$x,$y, $newImgWidth, $newImgHeight);
			//imagecopyresampled($dstImg, $srcImg,0,0,$x,$y, $newImgWidth, $newImgHeight,$oldImgWidth, $oldImgHeight);
			$smallImgName = $filePath.$saveFileName;
			self::imageToExt($oldImgInfo[2],$dstImg,$smallImgName);
			imagedestroy($srcImg);
			return $saveFileName;
		}
		
		
		/*
		 * 得到一个文字水印的真彩图
		 */
		public static function srcImgWatermarkText($imgFile,$imgInfo,$watermarkOptionType="",$r="255",$g="255",$b="255"){
			$srcImg = self::imgCreateFromExt($imgInfo[2],$imgFile);
			$textColor = imagecolorallocate($srcImg, $r, $g, $b);
			if(!$watermarkOptionType){
				$watermarkOptionType=self::$watermarkOptionType;
			}
			$option = self::watermarkOption($imgInfo,$watermarkOptionType,"text");
			imagettftext($srcImg, self::$fontSize,self::$angle, $option["x"], $option["y"], $textColor, self::$watermarkFont, self::$textContent);
			return $srcImg;
		}
		//生成一个文字水印的图片。
		public static function createTextWatermark($imgFile,$watermarkOptionType=""){
			$imgInfo = getimagesize($imgFile);
			if($imgInfo[0]>self::$watermarkImgSize){
				$fileInfo = FileUtil::getFileInfo($imgFile);
				$srcImg = self::srcImgWatermarkText($imgFile,$imgInfo,$watermarkOptionType,255,255,255);
				self::imageToExt($imgInfo[2],$srcImg,$imgFile);
				return true;
			}else{
				return false;	
			}
		}
		/*
		 * 得到一个图片水印的真彩图
		 */
		public static function srcImgWatermarkImg($imgFile,$imgInfo,$watermarkOptionType=""){		
			$srcImg = self::imgCreateFromExt($imgInfo[2],$imgFile);	//需要水印的图片
			
			$watermarkImgInfo = getimagesize(self::$watermarkImg);					//水印图
			$srcWatermarkImg = self::imgCreateFromExt($watermarkImgInfo[2],self::$watermarkImg);
			if(!$watermarkOptionType){
				$watermarkOptionType=self::$watermarkOptionType;
			}
			$option = self::watermarkOption($imgInfo,$watermarkOptionType,"image",$watermarkImgInfo);
			
			imagecopymerge($srcImg,$srcWatermarkImg,$option["x"],$option["y"],0,0,$watermarkImgInfo[0],$watermarkImgInfo[1],self::$watermarkClarity);
			return $srcImg;
		}
		//生成一个图片水印的图片。
		public static function createImgWatermark($imgFile,$watermarkOptionType=""){
			$imgInfo = getimagesize($imgFile);
			if($imgInfo[0]>self::$watermarkImgSize){
				$fileInfo = FileUtil::getFileInfo($imgFile);
				$srcImg = self::srcImgWatermarkImg($imgFile,$imgInfo,$watermarkOptionType);
				self::imageToExt($imgInfo[2],$srcImg,$imgFile);
				return true;
			}else{
				return false;	
			}
		}
		
		//以宽度为准
		public static function createImgWidth($oldImgWidth,$oldImgHeight,$newImgSize){
			$width = $newImgSize;
			$height = round(($oldImgHeight*$width)/$oldImgWidth);
			return array("width"=>$width,"height"=>$height);
		}
		//以高度为准
		public static function createImgHeight($oldImgWidth,$oldImgHeight,$newImgSize){
			$height = $newImgSize;
			$width = round(($oldImgWidth*$height)/$oldImgHeight);
			return array("width"=>$width,"height"=>$height);
		}
		
		public static function imageToExt($imgExt,$dstImg,$smallImgName){
			switch ($imgExt){
				case 1:
					$black = ImageColorAllocate($dstImg,0,0,0); 
					imagecolortransparent($dstImg,$black); 
					imagegif($dstImg,$smallImgName);
				break;
				case 2:
				//在这里需要注意。最后一个参数100表示图片的质量。100为最大。0最小。默认在75左右。100的话生成的图片有可能比原图要大。
					imagejpeg($dstImg,$smallImgName,80);
				break;
				case 3:
					imagepng($dstImg,$smallImgName);
				break;
			}
		}
		public static function imgCreateFromExt($imgExt,$imgFile){
			switch($imgExt){
				case 1:
					$srcImg=imagecreatefromgif($imgFile);
				break;
				case 2:
					$srcImg=imagecreatefromjpeg($imgFile);
				break;
				case 3:
					$srcImg=imagecreatefrompng($imgFile);
				break;
			}
			return $srcImg;
		}
		public static function watermarkOption($srcImgSize,$optionType="bottomRight",$watermarkType="text",$watermarkImgSize=""){
			if($watermarkType=="text"){
				$textNum = strlen(self::$textContent);
				$watermarkObjWidth = self::$fontSize*$textNum/3*1.35;
				$watermarkObjHeight = self::$fontSize*0.3;
			}elseif($watermarkType=="image"){
				$watermarkObjWidth = $watermarkImgSize[0];
				$watermarkObjHeight = $watermarkImgSize[1];
			}
			switch ($optionType){
				case "topLeft":
					$optionArr = array("x"=>20,"y"=>$watermarkObjHeight+40);
				break;
				case "topRight":
					$optionArr = array("x"=>$srcImgSize[0]-$watermarkObjWidth-20,"y"=>$watermarkObjHeight+40);
				break;
				case "bottomLeft":
					$optionArr = array("x"=>20,"y"=>$srcImgSize[1]-$watermarkObjHeight-20);
				break;
				case "bottomRight":
					$optionArr = array("x"=>$srcImgSize[0]-$watermarkObjWidth-20,"y"=>$srcImgSize[1]-$watermarkObjHeight-20);
				break;
				case "center":
					$optionArr = array("x"=>($srcImgSize[0]-$watermarkObjWidth)/2,"y"=>($srcImgSize[1]-$watermarkObjHeight)/2);
				break;
			}
			return $optionArr;
		}
	}
//	
//	ImageUtil::$textContent="我的水印内容";										//水印的内容
//	ImageUtil::$watermarkFont = "E:/Item/legou/yii/admin_module/simsun.ttc";	//水印的字体文件
//	ImageUtil::$watermarkImgSize = 100;											//需要水印的图片大小
//	ImageUtil::$fontSize = 24;													//水印字体的文字大小
//	ImageUtil::$watermarkImg = "E:/Item/legou/yii/Ihiyou_250.png";				//水印图片
//	ImageUtil::$watermarkClarity = 60;											//水印图片的透明度，值越大越不透明。最大100
//	ImageUtil::createSmallImg("E:/Item/legou/yii/a.png","_s",400,"",$minSize=200,$type="width","image","bottomLeft");
//	ImageUtil::createTextWatermark("E:/Item/legou/yii/a.png","bottomLeft");
//	ImageUtil::createImgWatermark("E:/Item/legou/yii/a.png","center");
?>