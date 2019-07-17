<?php

class indexController extends publicHomeController
{

	private $workItemOrderStatus = [
        1 => '<span style="color: #FF0000;">新添加</span>',
        2 => '<span style="color: blue;">处理中</span>',
        3 => '<span style="color: green;">处理完成</span>',
        4 => '<span style="color: #ccc;">关闭</span>'
    ];
    private $workItemStatus = [
        1 => '<span style="color: #FF0000;">新添加</span>',
        2 => '<span style="color: blue;">已接单</span>',
        3 => '<span style="color: green;">已回复</span>',
        4 => '<span style="color: #ccc;">完成</span>'
    ];
    public function show() {
        utils::pexit($_SESSION);
    }

	public function chamberlain() {
        //$smsService = utils::getService('sms');
        //$smsService->sendJoinSmsMessage('15868454077','5645');
	    $this->setView();
    }
    public function chamberlainList() {
        $sessionService = utils::getService('session');
        $workItemService = utils::getService('workItem');
        $userId = $sessionService->getUserLogin();
        $workItemList = $workItemService->getUserWorkItem($userId,[1,2,3]);
        $data = [
            'workItemList' => $workItemList
        ];
        $this->setView($data);
    }


    public function chamberlainDetail($param) {
        $workItemId = isset($param[0]) ? $param[0] : '';
        if(empty($workItemId)) {
            header("HTTP/1.1 404 Not Found");exit;
        }
        $workItemService = utils::getService('workItem');
        $workItemInfo = $workItemService->getWorkItemId($workItemId);

        $data = [
            'workItemInfo' => $workItemInfo
        ];


        $isMobile = false;
        if(preg_match("/android.*mobile|windows phone|iphone/iUs",preg_replace("/\[.*\]/Us", '', $_SERVER['HTTP_USER_AGENT']))){
            $isMobile = true;
        }
        if(!$isMobile) {
            $this->setView($data);
        } else {
            $this->setView($data, 'index/chamberlainDetailMobile');
        }
    }


    public function sendMessage() {
	    $sendType = strtoupper(request::postParam('sendType','login'));
        $phoneNum = request::postParam('phoneNum','');
        $errorMessage = [];
        $pregPhone='/^1[34578]\d{9}$/ims';
        if(!preg_match($pregPhone,$phoneNum)){
            $errorMessage['phoneNumError'] = '你的手机号码有误,无法通过验证!';
        }
        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }

        $sessionService = utils::getService('session');
        $messageCode = rand(100000,999999);
        switch ($sendType) {
            case 'LOGIN':
                $sessionService->setLoginMessageCode($messageCode);
                break;
            case 'REGISTER':
                $sessionService->setRegisterMessageCode($messageCode);
                break;
        }
        $smsService = utils::getService('sms');
        $smsService->sendJoinSmsMessage($phoneNum,$messageCode);
        $responseData = [
            'code' => 200,
            'data' => [],
            'message' => 'success'
        ];
        $this->reponseJsonData($responseData);
    }


    public function _after()
    {
        //echo '这个是控制器后置方法';
    }
    public function _before()
    {
        $this->coustomData['menuList'] = 'ar';
        //echo '这个是控制器前置方法';
    }
    public function index($param)
    {
        $addressService = utils::getService('address');
        $addressService->getAllAddress();

		$keyWords = request::getParam('keywords','');
		$page = request::getParam('page',1);
		$pageSize = request::getParam('pageSize',40);
		if($pageSize > 40 || $pageSize < 20) {
			$pageSize = 40;
		}

		$couponService = utils::getService('coupon');
		$brandList = $couponService->getCacheFileBrandList();
		$filterList = $this->getFilterUrl($param);

		$sortList = $this->getSortUrl($param);
		$filterNameArr = $this->getFilterName($param);
		$sortName = $this->getSortName($param);

		$isHomeCurrent = false;
		if(count($filterNameArr) == 1 && $filterNameArr[0] == 'default') {
			$isHomeCurrent = true;
		}

		$brandIdArr = array();
		if($keyWords) {
			$brandService = utils::getService('brand');
			$searchBrandList = $brandService->getBrandListByKeywords($keyWords);
			foreach($searchBrandList as $brandInfo) {
				$brandIdArr[] = $brandInfo['id'];
			}
		}

		$whereParam = array(
			'where' => 'id > 0',
			'param' => array(),
		);
		if($brandIdArr) {
			$whereParam['where'] .= ' and brandId in('.implode(',',$brandIdArr).')';
		}
		if($sortName != 'default') {
			$whereParam['order'] = $sortList[$sortName]['field'].' '.$sortList[$sortName]['order'];
		}

		if(count($filterNameArr) == 1 && $filterNameArr[0] == 'default') {

		} else {
			$filterTypeId = array();
			$filterTypeName = '';
			foreach ($filterNameArr as $filterName) {
				if($filterName != 'default') {
					if(empty($filterTypeName)) {
						$filterTypeName = $filterList[$filterName]['field'];
					}
					$filterTypeId[] = $filterList[$filterName]['id'];
				}
			}
			if($filterTypeId) {
				$whereParam['where'] .= ' and '.$filterTypeName.' in ('.implode(',',$filterTypeId).')';
			}
		}

		$couponList = array();
		$couponCount = 0;
		$showPageStr = '';
		$couponResult = $couponService->getCouponList($whereParam,$page,$pageSize);
		if(isset($couponResult['resultList']) && $couponResult['resultList']) {
			$couponList = $this->formartCouponList($couponResult['resultList'], $brandList);
			$couponCount = $couponResult['count'];
			$pageObj = new Page();
			$showPageStr = $pageObj->showpage($couponCount, $page, $pageSize);
		}
		$data = array(
			'couponList' => $couponList,
			'filterList' => $filterList,
			'sortList' => $sortList,
			'couponCount' => $couponCount,
			'showPageStr' => $showPageStr,
			'keyWords' => $keyWords,
			'isHomeCurrent' => $isHomeCurrent
		);
        $this->setView($data);
    }




    public function passwordLogin() {
        $phoneNum = request::postParam('phoneNum');
        $password = request::postParam('password');
        $errorMessage = [];
        $pregPhone='/^1[34578]\d{9}$/ims';
        if(!preg_match($pregPhone,$phoneNum)){
            $errorMessage['phoneNumError'] = '你的手机号码有误,无法通过验证!';
        }
        if(empty($password) || strlen($password) < 6) {
            $errorMessage['passwordError'] = '密码输入有误';
        }
        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }

        $workUserService = utils::getService('workUser');
        $userInfo = $workUserService->getUserInfoByPhone($phoneNum);
        if(empty($userInfo)) {
            $errorMessage['userError'] = '账户密码不匹配，请确认后重新输入。';
        }
        $userPassword = $workUserService->createUserPassword($password);
        if (isset($userInfo['password']) && $userInfo['password'] != $userPassword) {
            $errorMessage['userError'] = '账户密码不匹配，请确认后重新输入。';
        }
        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }
        $responseData = [
            'code' => 200,
            'userId' => $userInfo['id'],
            'message' => 'success'
        ];
        $sessionService = utils::getService('session');
        $sessionService->setUserLogin($userInfo['id']);
        $this->reponseJsonData($responseData);
    }


    public function smsLogin() {
        $phoneNum = request::postParam('phoneNum');
        $phoneNumCode = request::postParam('phoneNumCode');
        $errorMessage = [];
        $pregPhone='/^1[34578]\d{9}$/ims';
        if(!preg_match($pregPhone,$phoneNum)){
            $errorMessage['phoneNumError'] = '你的手机号码有误,无法通过验证!';
        }
        if(!is_numeric($phoneNumCode) || strlen($phoneNumCode) != 6) {
            $errorMessage['phoneNumCodeError'] = '短信验证码错误';
        }
        $sessionService = utils::getService('session');
        if(!$sessionService->checkMessageCode($phoneNumCode,'login')) {
            $errorMessage['phoneNumCodeError'] = '无效验证码,请从新获取';
        }

        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }
        $workUserService = utils::getService('workUser');
        $userInfo = $workUserService->getUserInfoByPhone($phoneNum);
        if(empty($userInfo)) {
            $errorMessage['userError'] = '账户不存在，请确认后重新输入。';
        }
        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }
        $responseData = [
            'code' => 200,
            'userId' => $userInfo['id'],
            'message' => 'success'
        ];

        $sessionService = utils::getService('session');
        $sessionService->setUserLogin($userInfo['id']);
        $this->reponseJsonData($responseData);
    }

    public function register() {
	    $phoneNum = request::postParam('phoneNum');
        $phoneNumCode = request::postParam('phoneNumCode');
        $password = request::postParam('password');

        $errorMessage = [];
        $pregPhone='/^1[34578]\d{9}$/ims';
        if(!preg_match($pregPhone,$phoneNum)){
            $errorMessage['phoneNumError'] = '你的手机号码有误,无法通过验证!';
        }
        if(!is_numeric($phoneNumCode) || strlen($phoneNumCode) != 6) {
            $errorMessage['phoneNumCodeError'] = '短信验证码错误';
        }
        if(empty($password) || strlen($password) < 6) {
            $errorMessage['passwordError'] = '密码输入有误';
        }
        $sessionService = utils::getService('session');
        if(!$sessionService->checkMessageCode($phoneNumCode,'register')) {
            $errorMessage['phoneNumCodeError'] = '无效验证码,请确认后重新输入';
        }
        if($errorMessage) {
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }

        $data = [
            'phoneNum' => $phoneNum,
            'password' => $password,
        ];

        $workUserService = utils::getService('workUser');

        $userInfo = $workUserService->getUserInfoByPhone($phoneNum);
        if(!empty($userInfo)) {
            $errorMessage['userError'] = '您已注册过，请选择登录。';
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'error'
            ];
            $this->reponseJsonData($responseData);
        }
        $userId = $workUserService->addUserInfo($data);
        $responseData = [
            'code' => 200,
            'userId' => $userId,
            'message' => 'success'
        ];

        $sessionService = utils::getService('session');
        $sessionService->setUserLogin($userId);
        $this->reponseJsonData($responseData);
    }


    public function submitWorkItem() {
        $workItemContent = request::postParam('workItemContent');
        $base64ImageList = request::postParam('base64ImageList');

        if(empty($workItemContent)) {
            $errorMessage = [
                'contentError' => '请详细描述您的购物需求。'
            ];
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'success'
            ];
            $this->reponseJsonData($responseData);
        }
        $data = [
            'detailDesc' => $workItemContent,
        ];
        $workItemService = utils::getService('workItem');
        $workItemId = $workItemService->insertWorkItem($data);
        if($workItemId) {
            if($base64ImageList) {
                foreach ($base64ImageList as $imageItem) {
                    $dataImage = [
                        'imgUrl' => $imageItem,
                        'workItemId' => $workItemId
                    ];
                    $workItemService->insertWorkItemImage($dataImage);
                }
            }
            $responseData = [
                'code' => 200,
                'data' => ['success' => '已收到您的需求，管家会及时安排为您服务'],
                'message' => 'success'
            ];
            $this->reponseJsonData($responseData);
        } else {
            $errorMessage = [
                'contentError' => '添加失败,请重新尝试。'
            ];
            $responseData = [
                'code' => 400,
                'data' => $errorMessage,
                'message' => 'success'
            ];
            $this->reponseJsonData($responseData);
        }

    }


    public function base64Upload() {
        $base64Image = request::postParam('base64Image','');
        $imageStr = $base64Image;
        $up_dir = './uploads/images/workItem/';//存放在当前目录的upload文件夹下
        if(!file_exists($up_dir)){
            mkdir($up_dir,0777);
        }
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $imageStr, $result)){
            $type = $result[2];
            if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
                $new_file = $up_dir.utils::getUniqueValue().'.'.$type;
                if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $imageStr)))){
                    $imgPath = '/'.str_replace('./', '', $new_file);
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



    public function workItemList() {
        $workItemService = utils::getService('workItem');
        $workItemList = $workItemService->getWorkItem();
        $workItemStatus = $this->workItemStatus;
        $data = [
            'workItemList' => $workItemList,
            'workItemStatus' => $workItemStatus
        ];
        $this->setView($data);
    }

    public function workItemToOrder($param) {
        $workItemId = isset($param[0]) ? $param[0] : '';
        if($workItemId) {
            $workItemService = utils::getService('workItem');
            $workItemInfo = $workItemService->getWorkItemId($workItemId);
            $workItemStatus = $this->workItemStatus;
            $data = [
                'workItemInfo' => $workItemInfo,
                'workItemStatus' => $workItemStatus
            ];
            $this->setView($data);
        }
    }

    public function submitItemOrder() {
        $workItemOrderContent = request::postParam('workItemOrderContent');
        $imageIdStr = request::postParam('imageIdStr');
        $workItemId = request::postParam('workItemId');

        $data = [
            'detailDesc' => $workItemOrderContent,
            'imageIdStr' => $imageIdStr,
            'categoryId' => 1,
            'workItemId' => $workItemId,
            'serviceUserId' => 1,
            'status' => 1
        ];

        $workItemOrderService = utils::getService('workItemOrder');
        $orderId = $workItemOrderService->insertWorkItemOrder($data);

        //文件类型错误
        $this->reponseJsonData(
            array('code' => 200,'message'=>'success')
        );

    }





    public function workItemOrderList($param = []) {
        $workItemId = isset($param[0]) ? $param[0] : '';
        $workItemOrderService = utils::getService('workItemOrder');
        $orderList = $workItemOrderService->getWorkItemOrderList($workItemId);
        $data = [
            'orderList' => $orderList['itemOrderList'],
            'orderItemStatus' => $this->workItemOrderStatus
        ];
        $this->setView($data);
    }

    public function workItemInfo() {
        $workItemOrderId = request::getParam('workItemOrderId');
        $workItemOrderService = utils::getService('workItemOrder');
        $workItemOrderInfo = $workItemOrderService->getWorkItemInfo($workItemOrderId);
        $data = [
            'workItemOrderInfo' => $workItemOrderInfo,
            'orderItemStatus' => $this->workItemOrderStatus
        ];
        $this->setView($data);
    }


    public function saveWorkOrderContent() {
        $workItemContent = request::postParam('workItemContent');
        $workItemOrderId = request::postParam('workItemOrderId');
        $data = [
            'workItemOrderAnswer' => $workItemContent,
            'workItemOrderId' => $workItemOrderId
        ];

        $workItemOrderService = utils::getService('workItemOrder');
        $workItemOrderService->insertWorkItemOrderAnswer($data);
        //文件类型错误
        $this->reponseJsonData(
            array('code' => 200,'message'=>'success')
        );
    }

    public function saveWorkOrderProduct() {
        $productName = request::postParam('productName','');
        $productImage = request::postParam('productImage','');
        $productPrice = request::postParam('productPrice',0);
        $productOrgPrice = request::postParam('productOrgPrice',0);
        $orgProductUrl = request::postParam('orgProductUrl','');
        $workItemOrderId = request::postParam('workItemOrderId','');
        $data = [
            'productName' => $productName,
            'productImage' => $productImage,
            'price' => $productPrice,
            'orgPrice' => $productOrgPrice,
            'orgProductUrl' => $orgProductUrl,
        ];

        $workItemOrderService = utils::getService('workItemOrder');
        $workItemOrderService->insertWorkItemOrderProduct($data, $workItemOrderId);
        //文件类型错误
        $this->reponseJsonData(
            array('code' => 200,'message'=>'success')
        );
    }

}

?>