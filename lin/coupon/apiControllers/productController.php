<?php
	class productController extends apiController
	{
		public function add() {
		    $allProductData = request::requestInputParam();
		    $productInfo = $allProductData['productInfo'];
            $productDesc = $allProductData['productDesc'];
            $skuList = $allProductData['skuList'];


            $userInfo = utils::$userInfo;
            if(empty($userInfo)) {
                $this->reponseJsonDataNew(400,[],'用户信息获取有问题，请你重新登录！');
            }

            if(!isset($productInfo['productName']) || empty($productInfo['productName']) ) {
                $this->reponseJsonDataNew(400,[],'商品名称必须填写！');
            }
            if(!isset($productInfo['productPrice']) || empty($productInfo['productPrice']) ) {
                $this->reponseJsonDataNew(400,[],'商品价格必须设置！');
            }
            $productData = [
                'productName' => $productInfo['productName'],
                'userId' => $userInfo['id'],
                'productPrice' => $productInfo['productPrice'],
                'expireDay' => isset($productInfo['expireDay']) && $productInfo['expireDay'] <= 20 ? $productInfo['expireDay'] : 20,
                'shippingPrice' => isset($productInfo['shippingPrice']) && $productInfo['shippingPrice'] > 0 ? $productInfo['shippingPrice'] : 0,
            ];
            $productDescInfoList = [];
            foreach ($productDesc as $descInfo) {
                $info = [];
                if($descInfo['code'] == 'image') {
                    $info = [
                        'code' => 'image',
                        'imageUrl' => $descInfo['imageUrl'],
                        'textContent' => $descInfo['imageText']
                    ];
                }
                if($descInfo['code'] == 'text') {
                    $info = [
                        'code' => 'text',
                        'textContent' => $descInfo['textContent']
                    ];
                }
                $productDescInfoList[] = $info;
            }
            $skuData = [];
            if($skuList) {
                $skuData = $this->formatSku($skuList);
            }
            $productService = utils::getService('product');
            $productId = $productService->insetProduct($productData,$productDescInfoList,$skuData);
            if($productId) {
                $this->reponseJsonDataNew(200,[],'商品发布成功 !');
            } else {
                $this->reponseJsonDataNew(400,[],'添加失败，数据有问题，请联系管理员');
            }
        }


        private function formatSku($requestSkuList) {
            $skuData = $skuList = [];
            foreach ($requestSkuList as $skuInfo) {
                $skuData['title0'][] =$skuInfo['skuOneName'];
                $skuData['name0'][] =$skuInfo['skuOneNameVal'];
                if($skuInfo['skuTwoName']) {
                    $skuData['title1'][] =$skuInfo['skuTwoName'];
                    $skuData['name1'][] =$skuInfo['skuTwoNameVal'];
                }
                $skuData['price'][] = $skuInfo['skuPrice'];
            }

            if($skuData){
                if(isset($skuData['name0']) && !empty($skuData['name0'])){
                    $hasSkuTwo = false;
                    if(isset($skuData['name1']) && $skuData['name1']){
                        $hasSkuTwo = true;
                    }
                    $i = 0;
                    foreach($skuData['name0'] as $skukey=>$skuval){
                        if(!isset($skuIdArr[$skuval])){
                            $i++;
                            $skuIdArr[$skuval] = $i;
                        }
                        $skuStr = $skuData['title0'][$skukey].'::'.$skuval;
                        $skuIdStr = '1:'.$skuIdArr[$skuval];
                        if($hasSkuTwo){
                            $titleValue = $skuData['name1'][$skukey];
                            $skuStr .= '||'.$skuData['title1'][$skukey].'::'.$titleValue;
                            if(!isset($skuIdArr[$titleValue])){
                                $i++;
                                $skuIdArr[$titleValue] = $i;
                            }
                            $skuIdStr .= '|2:'.$skuIdArr[$titleValue];
                        }
                        $skuList[$skukey]['skuTitle'] = $skuStr;
                        $skuList[$skukey]['skuTitleId'] = $skuIdStr;
                        if(isset($skuData['id'][$skukey])){
                            $skuList[$skukey]['id'] = $skuData['id'][$skukey];
                        }
                        $skuList[$skukey]['price'] = $skuData['price'][$skukey];
                    }
                }
            }

            return $skuList;
        }


        public function uploadFile() {
            if($_FILES) {
                $fileArr = [];
                $savePathDocument = STATICPATH.'uploads/images/product/';
                FileUtil::createDir($savePathDocument);
                $file = $_FILES['file'];
                $fileParts = pathinfo($file['name']);
                $fileName = utils::getUniqueValue();
                $fileExt = $fileParts['extension'];
                $allFileName = $fileName .'.' . $fileExt;
                $result = fileUpload::customSaveFile($file,$savePathDocument, $allFileName);
                $resultFile = '/uploads/images/product/'.$allFileName;
                $this->reponseJsonDataNew(200,['imagePath' => $resultFile],'success');
            }
        }



	}
?>