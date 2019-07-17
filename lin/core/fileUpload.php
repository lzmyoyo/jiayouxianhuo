<?php
class fileUpload{
	public static function saveFile($filePath,$fileName=''){
		if(isset($_FILES['file'])){
			$tempFile = $_FILES['file'];
			if(empty($fileName)){
				$fileName = $tempFile["name"];
			}
			move_uploaded_file($tempFile["tmp_name"],$filePath.$fileName);
			return true;
		}else{
			return false;
		}
	}

    public static function saveFileToFile($tempFile,$filePath,$fileName=''){
	    $fileAllName = $filePath.$fileName;
        move_uploaded_file($tempFile,$fileAllName);
        return $fileAllName;
    }

    public static function customSaveFile($file,$filePath,$fileName='')
    {
        $tempFile = $file;
        if (empty($fileName)) {
            $fileName = $tempFile["name"];
        }
        move_uploaded_file($tempFile["tmp_name"], $filePath . $fileName);
        return true;
    }
}
?>