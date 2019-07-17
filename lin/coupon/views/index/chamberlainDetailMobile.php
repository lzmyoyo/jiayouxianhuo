<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $workItemInfo['detailDesc'];?></title>
    <meta name="description" content="<?php echo $workItemInfo['detailDesc'];?>">
    <meta id="viewport" name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1user-scalable=no,viewport-fit=cover">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="http://www.jq22.com/demo/jQueryViewer20160329/css/viewer.min.css" />
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="/localResizeIMG.js"></script>
    <script type="text/javascript" src="/mobileBUGFix.mini.js"></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?a0eb65e186f62751668bfdcb47638d44";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
</head>
<style>
    body,p,ul,li,h5{
        margin: 0px;
        padding:0px;
        list-style: none;
    }

    .main{
        width: 100%;
        margin: 0px auto;
        padding:0px 10px
    }
    .main-title{
        padding:10px 0px
    }


    .main-input textarea{
        box-shadow:none;
        border:0;
        outline:none;
    }
    .guanjia-detial{
        border-top: 1px solid #ccc;
    }
    .guanjia-detial p{
        padding:20px 0px 10px 0px;
        font-size:18px;
        font-weight: bold;
    }
    .guanjia-detial-list p{
        padding:0px 0px 10px 0px;
        font-size:14px;
        font-weight: bold;
    }
    .guanjia-detial-list{
        border-top: none;
    }
    .guanjia-detial ul{
        padding:0px 0px 10px 0px;
    }
    .guanjia-detial-list ul li{
        font-size:14px
    }

    .show-image{
        width:100px; border:solid #ccc 1px;display: inline-block;text-align: center;
        float: left;padding:5px;
        border: solid #ccc 1px;;
        margin-right: 10px;
    }
    .show-image:after{
        clear: both;
    }
    .show-image img{
        width: 100%;
    }
    .title-chamberlain{
        font-size:18px;
        padding:10px 0px 10px 0px
    }
    .guanjia-detial .chamberlain-content p{
        font-weight:normal;
        line-height:24px;
        padding: 5px 0px;
        font-size:14px
    }
    .chamberlain-content{
        font-size:14px;
        padding-bottom: 10px;
    }
    .top-menu{
        height:40px;
        line-height: 40px;
        border-bottom: solid #eee 1px;
        margin-bottom: 10px;
    }
    .top-menu a:hover{
        text-decoration: none;
        display: inline-block;
    }
    .top-logo{
        float: left;
    }
    .top-center{
        float: right;
    }
</style>
<body>
    <div class="main">
        <div class="top-menu">
            <div class="top-logo">
                购物管家。
            </div>
            <div class="top-center">
                <a href="/index/chamberlainList">
                    <span class="glyphicon glyphicon-user"></span>
                    <label>我的购物需求</label>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="/index/chamberlain">
                    <span class="glyphicon glyphicon-question-sign"></span>
                    <label>找管家帮忙</label>
                </a>
            </div>
            <div style="clear: both"></div>
        </div>
        <div class="main-input">
            <p class="title-chamberlain"><?php echo $workItemInfo['detailDesc'];?></p>
            <?php if($workItemInfo['imageList']) { ?>
            <div style="padding:0px 0px 20px 0px">
                <div class="show-image-list" id="chose_pic_btn">
                    <?php foreach($workItemInfo['imageList'] as $imageInfo) { ?>
                    <div class="show-image"><img src="<?php echo $imageInfo['imgUrl'];?>" /></div>
                    <?php } ?>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <?php } ?>
            <?php if($workItemInfo['workItemOrderInfo']) { ?>
            <div class="guanjia-detial guanjia-detial-list">
                <p>管家笔录:</p>
                <ul>
                    <?php foreach($workItemInfo['workItemOrderInfo'] as $orderIndex => $orderInfo) { ?>
                    <li>
                        <?php echo $orderIndex+1 ;?>、
                        <?php echo $orderInfo['detailDesc'] ;?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
            <?php foreach($workItemInfo['workItemOrderInfo'] as $orderIndexDetail => $orderInfoDetail) { ?>
            <div class="guanjia-detial">
                <p><?php echo $orderIndexDetail+1 ;?>、<?php echo $orderInfoDetail['detailDesc'] ;?></p>
                <article class="chamberlain-content">
                    <?php if (isset($orderInfoDetail['answerInfo']['workItemOrderAnswer']) && $orderInfoDetail['answerInfo']['workItemOrderAnswer']) { ?>
                    <?php echo $orderInfoDetail['answerInfo']['workItemOrderAnswer'];?>
                    <?php } else { ?>
                    请你耐心等待，管家正在为您搜集相关资料。
                    <?php } ?>
                </article>


                <?php if(isset($orderInfoDetail['productList']) && $orderInfoDetail['productList']) { ?>
                <div>
                    <div>
                        推荐商品:
                    </div>
                    <ul style="padding: 0px">
                        <?php foreach($orderInfoDetail['productList'] as $productInfo) { ?>
                        <li style="padding:10px;" onmouseover="bg(this);" onmouseout="obg(this)">
                            <a href="<?php echo $productInfo['orgProductUrl'];?>">
                                <div style="float:left; width:100px;">
                                    <img style="width: 100%" src="<?php echo $productInfo['productImage'];?>">
                                </div>
                                <div style="float: left; margin-left:10px; line-height: 20px; ">
                                    <div><?php echo $productInfo['productName'];?></div>
                                    <label style="color:#ff0000; font-size: 18px; display: inline-block;margin-top:10px"><?php echo $productInfo['price'];?></label>
                                </div>
                                <div style="clear: both;"></div>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php include 'login-module.php';?>
    <script type="application/javascript" src="http://www.jq22.com/demo/jQueryViewer20160329/js/viewer.min.js"></script>
    <script>
        var picArr = new Array();// 存储图片
        $('input:file').localResizeIMG({
            width:800,// 宽度
            quality: 0.8, // 压缩参数 1 不压缩 越小清晰度越低
            success: function (result) {
                var img = new Image();
                img.src = result.base64;
                var _str = '<div class="show-image"><img src="'+img.src+'" /><span class="delete_pic">X</span></div>';
                $('#chose_pic_btn').before(_str);
                var _i =  picArr.length
                picArr[_i] = result.base64;
                // picArr[_i] = _i;
                console.log(picArr)
            }
        });
        // 删除
        $(document).on('click', '.delete_pic', function(event) {
            var aa = $(this).parents(".show-image").index();
            picArr.splice(aa,1);
            $(this).parents(".show-image").remove();
            console.log(picArr);
        });


    </script>
    <script type="text/javascript">
        var bgcolor = '';
        function bg(trthis){
            bgcolor =  trthis.style.backgroundColor;
            trthis.style.backgroundColor = '#eee';
        }
        function obg(trthis){
            trthis.style.backgroundColor = bgcolor;
        }

        var viewer = new Viewer(document.getElementById('chose_pic_btn'),
            {
                navbar: true,
                title:false,
                toolbar:true,
                tooltip:true,
            }
        );

    </script>
</body>
</html>