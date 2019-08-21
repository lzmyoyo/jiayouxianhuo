<?php

class productService
{
    public function insetProduct($productInfo, $productDesc, $skuData) {
        $nowTime = time();
        $productId = 0;
        $firstImage = '';
        //取详情介绍里面的第一张图作为商品的主图，列表显示
        if($productDesc) {
            foreach ($productDesc as $descInfo) {
                if($descInfo['code'] == 'image'){
                    $firstImage = $descInfo['imageUrl'];
                    break;
                }
            }
        }

        if($productInfo) {
            $productInfo['addTime'] = $nowTime;
            $productInfo['productImage'] = $firstImage;
            $productModel = new productModel();
            $productId = $productModel->insert($productInfo);
        }
        if($productId) {
            if($productDesc) {
                $productDescModel = new productDescModel();
                foreach ($productDesc as $descInfo) {
                    $descData = $descInfo;
                    $descData['addTime'] = $nowTime;
                    $descData['productId'] = $productId;
                    $productDescModel->insert($descData);
                }
            }

            if($skuData) {
                $productSkuModel = new productSkuModel();
                foreach ($skuData as $skuInfo) {
                    $skuData = $skuInfo;
                    $skuData['addTime'] = $nowTime;
                    $skuData['productId'] = $productId;
                    $productSkuModel->insert($skuData);
                }
            }

        }
        return $productId;

    }

}

?>