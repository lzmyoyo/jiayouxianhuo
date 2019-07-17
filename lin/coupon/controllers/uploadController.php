<?php
    class uploadController extends publicHomeController
    {
        public function __construct(){

        }

        public function base64Upload() {
            $requestData = $this->getRequestData();
            $imageStr = $requestData['image'];
            $up_dir = './uploads/images/workItem/user/';//存放在当前目录的upload文件夹下
            if(!file_exists($up_dir)){
                mkdir($up_dir,0777);
            }
            if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $imageStr, $result)){
                $type = $result[2];
                if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
                    $new_file = $up_dir.utils::getUniqueValue().'.'.$type;
                    if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imageStr)))){
                        $imgPath = utils::config('siteUrl').'/'.str_replace('./', '', $new_file);
                        $this->reponseJsonData(
                            array('code' => 200,'message'=>'图片上传成功','data' => ['image' => $imgPath])
                        );
                    }else{
                        $this->reponseJsonData(
                            array('code' => 400,'message'=>'图片上传失败')
                        );
                    }
                }else{
                    //文件类型错误
                    $this->reponseJsonData(
                        array('code' => 400,'message'=>'图片上传类型错误')
                    );
                }
            }else{
                //文件错误
                $this->reponseJsonData(
                    array('code' => 400,'message'=>'文件错误')
                );
            }

        }

        public function uploadFile() {
            if($_FILES) {
                $fileArr = [];
                $savePathDocument = STATICPATH.'uploads/images/workItem/serverUser/';
                FileUtil::createDir($savePathDocument);
                foreach ($_FILES as $file) {
                    $fileParts = pathinfo($file['name']);
                    $fileName = utils::getUniqueValue();
                    $fileExt = $fileParts['extension'];
                    $allFileName = $fileName .'.' . $fileExt;
                    $result = fileUpload::customSaveFile($file,$savePathDocument, $allFileName);
                    $resultFile = '/uploads/images/workItem/serverUser/'.$allFileName;
                    $fileArr[] = $resultFile;
                }
                $this->reponseJsonData(
                    array('errno' => 0,'data' => $fileArr,'code' => 200,'message'=>'success')
                );
            }
        }




        public function index(){
            include_once '/Users/linzhimin/allItem/coupon/lin/core/phpqrcode.php';
            echo QRcode::png('http://dev.coupon.com/team/'.md5('浙A668858'), 'file.png',QR_ECLEVEL_L, 5);
        }
        public function userIdentityImg(){
            $savePathDocument = STATICPATH.'uploads/images/user/identityimg/';
            FileUtil::createDir($savePathDocument);
            $fileParts = pathinfo($_FILES['file']['name']);
            $fileExt = $fileParts['extension'];
            $userId = 2;
            $fileName = 'identity_'.$userId;
            $allFileName = $fileName .'.' . $fileExt;
            $result = fileUpload::saveFile($savePathDocument, $allFileName);
			$data = array(
				'fileName' => '/uploads/images/user/identityimg/'.$allFileName
			);
			utils::resposeJson($data);
        }
        public function uploadProductImage(){
            $savePathDocument = STATICPATH.'uploads/images/product/';
            FileUtil::createDir($savePathDocument);
            $fileParts = pathinfo($_FILES['file']['name']);
            $fileExt = $fileParts['extension'];
            $time = 'sp_'.rand(0,9999).time();;
            $fileName = 'product_image_'.$time;
            $allFileName = $fileName .'.' . $fileExt;
            $result = fileUpload::saveFile($savePathDocument, $allFileName);
            $data = array(
                'fileName' => '/uploads/images/product/'.$allFileName
            );
            utils::resposeJson($data);
        }


        public function systemProductImg(){
            $saveFilePath = 'uploads/images/sysproduct/';
            $savePathDocument = STATICPATH.$saveFilePath;
            FileUtil::createDir($savePathDocument);
            $fileParts = pathinfo($_FILES['file']['name']);
            $fileExt = $fileParts['extension'];
            $fileName = 'sp_'.rand(0,9999).time();
            $allFileName = $fileName .'.' . $fileExt;
            $result = fileUpload::saveFile($savePathDocument, $allFileName);
            $defaultFilePath = $savePathDocument.$allFileName;
            $thumbFile = ImageUtil::createSmallImg($defaultFilePath,'_t200',200,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t300',300,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t450',450,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t600',600,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t1000',1000,"",1);
            $data = array(
                'fileName' => $saveFilePath.$thumbFile['fileName']
            );
            utils::resposeJson($data);
        }
        public function productImg(){
            $saveFilePath = 'uploads/images/product/';
            $savePathDocument = STATICPATH.$saveFilePath;
            FileUtil::createDir($savePathDocument);
            $fileParts = pathinfo($_FILES['file']['name']);
            $fileExt = $fileParts['extension'];
            $fileName = 'p_'.rand(0,9999).time();
            $allFileName = $fileName .'.' . $fileExt;
            $result = fileUpload::saveFile($savePathDocument, $allFileName);
            $defaultFilePath = $savePathDocument.$allFileName;
            $thumbFile = ImageUtil::createSmallImg($defaultFilePath,'_t200',200,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t300',300,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t450',450,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t600',600,"",1);
            ImageUtil::createSmallImg($defaultFilePath,'_t1000',1000,"",1);
            $data = array(
                'fileName' => $saveFilePath.$thumbFile['fileName']
            );
            utils::resposeJson($data);
        }
    }
?>