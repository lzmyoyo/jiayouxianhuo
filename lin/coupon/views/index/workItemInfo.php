<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Online Promo Codes: Save at Thousands of Sites</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="/localResizeIMG.js"></script>
    <script type="text/javascript" src="/mobileBUGFix.mini.js"></script>
</head>
<style>
    body,p,ul,li,h5{
        margin: 0px;
        padding:0px;
        list-style: none;
    }

    .main{
        width:930px;
        margin: 0px auto;
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
        padding:0px 10px 10px 10px;
    }
    .guanjia-detial-list ul li{
        padding-left:15px;
        line-height: 24px;
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
        padding:20px 0px
    }
    .chamberlain-content{
        font-size:14px;
        padding-bottom: 15px;
        padding-left:25px
    }

</style>
<body>

    <div class="main">
        <div class="main-input">

            <p class="title-chamberlain"><?php echo $workItemOrderInfo['detailDesc'];?></p>
            <?php if(isset($workItemOrderInfo['imageList']) && $workItemOrderInfo['imageList']) { ?>
            <div style="padding:0px 0px 20px 0px">
                <div class="show-image-list" id="chose_pic_btn">
                    <?php foreach ($workItemOrderInfo['imageList'] as $imageInfo) {?>
                    <div class="show-image"><img src="<?php echo $imageInfo['imgUrl'];?>" /></div>
                    <?php } ?>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <?php } ?>

            <div id="editor"><?php if(isset($workItemOrderInfo['workItemOrderAnswerInfo']['workItemOrderAnswer'])) { ?><?php echo $workItemOrderInfo['workItemOrderAnswerInfo']['workItemOrderAnswer'];?><?php } ?></div>
            <div style="text-align: right; margin-top: 10px;">
                <input type="hidden" id="workItemOrderId" value="<?php echo $workItemOrderInfo['id'];?>" />
                <button id="saveWorkOrderContent" style="height: 33px; width: 100px; border-radius: 5px; ">保存</button>
            </div>

            <?php if(isset($workItemOrderInfo['productList']) && $workItemOrderInfo['productList']) { ?>
            <div style="padding:10px 0px 0px 0px">
                已推荐的商品:
            </div>
            <div style="border-bottom: solid #ccc 1px">
                <?php foreach ($workItemOrderInfo['productList'] as $pkey => $productInfo) { ?>
                <div style="height:35px;line-height: 35px;">
                    <a href="<?php echo $productInfo['orgProductUrl'];?>"> <?php echo $productInfo['productName'];?></a>
                    <?php echo $productInfo['price'];?>
                </div>
                <?php } ?>

            </div>
            <?php } ?>
            <div>
                <div style="padding:10px 0px 0px 0px">
                    第三方平台商品链接地址:
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" class="form-control" placeholder="直接将平台商品的访问网址拷贝保存即可" />
                </div>
                <div style="padding:10px 0px 0px 0px">
                    手动录入商品:
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" id="productName" class="form-control" placeholder="商品名称" />
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" id="productImage" class="form-control" placeholder="图片地址" />
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" id="productPrice" class="form-control" placeholder="价格" />
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" id="productOrgPrice" class="form-control" placeholder="原价" />
                </div>
                <div style="padding:10px 0px 0px 0px">
                    <input type="text" id="orgProductUrl" class="form-control" placeholder="链接地址" />
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <button id="addOrderProduct" style="height: 33px; width: 100px; border-radius: 5px; ">保存</button>
                </div>
            </div>
        </div>

    </div>

    <!-- 注意， 只需要引用 JS，无需引用任何 CSS ！！！-->
    <script type="text/javascript" src="//unpkg.com/wangeditor/release/wangEditor.min.js"></script>
    <script type="text/javascript">
        var E = window.wangEditor
        var editor = new E('#editor')
        editor.customConfig.uploadImgShowBase64 = true   // 使用 base64 保存图片
        editor.customConfig.uploadImgParams = {
            // 如果版本 <=v3.1.0 ，属性值会自动进行 encode ，此处无需 encode
            // 如果版本 >=v3.1.1 ，属性值不会自动 encode ，如有需要自己手动 encode
            formEditor: 'true'
        }
        editor.customConfig.uploadImgServer = '/upload/uploadFile'  // 上传图片到服务器
        editor.customConfig.showLinkImg = false
        editor.create();



        $('#saveWorkOrderContent').on('click',function () {
            var workItemContent = editor.txt.html();
            var workItemOrderId = $('#workItemOrderId').val();

            $.ajax({
                url:'/index/saveWorkOrderContent',
                data:{'workItemContent':workItemContent,'workItemOrderId':workItemOrderId},
                type:'POST',
                dataType:'json',
                success:function (responseData) {
                    if(responseData.code == 200) {
                        window.location.reload();
                    } else {
                        alert('cuowu');
                    }
                }
            })



        });

        $('#addOrderProduct').on('click',function () {
            var workItemOrderId = $('#workItemOrderId').val();
            var productName = $('#productName').val();
            var productImage = $('#productImage').val();
            var productPrice = $('#productPrice').val();
            var productOrgPrice = $('#productOrgPrice').val();
            var orgProductUrl = $('#orgProductUrl').val();

            $.ajax({
                url:'/index/saveWorkOrderProduct',
                data:{
                    'productName':productName,
                    'productImage':productImage,
                    'productPrice':productPrice,
                    'productOrgPrice':productOrgPrice,
                    'orgProductUrl':orgProductUrl,
                    'workItemOrderId':workItemOrderId
                },
                type:'POST',
                dataType:'json',
                success:function (responseData) {
                    if(responseData.code == 200) {
                        window.location.reload();
                    } else {
                        alert('cuowu');
                    }
                }
            })



        })




    </script>
</body>
</html>