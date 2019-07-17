<?php
	class language {
		public static function getLanguage()
		{
			return cookie::getCookie('language');
		}


		public static function replaceLang($line,$replaceArrParam){
			if($replaceArrParam) {
				if(is_array($replaceArrParam)) {
					$indexKey = 1;
					foreach($replaceArrParam as $index=>$param) {
						$line = strReplaceOnce('%1%1',$param,$line);
						$line = str_replace('{'.$indexKey.'}',$replaceArrParam[$index],$line);
						$indexKey++;
					}
				} else {
					$line = strReplaceOnce('%1%1',$replaceArrParam,$line);
					$line = str_replace('{1}',$replaceArrParam,$line);
				}
			}
			return $line;
		}

		public static function lang($line, $replaceArrParam = array())
		{
			$languageConfig = utils::config('language.'.$line);
			$languageName = self::getLanguage();
			$lineResult = isset($languageConfig[$languageName]) ? $languageConfig[$languageName] : $line;
			$lineResult = self::replaceLang($lineResult,$replaceArrParam);
			return $lineResult;
		}
	}
?>