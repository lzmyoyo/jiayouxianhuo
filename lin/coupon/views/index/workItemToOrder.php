<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Online Promo Codes: Save at Thousands of Sites</title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="http://www.jq22.com/demo/jQueryViewer20160329/css/viewer.min.css" />
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
        padding:10px 0px 20px 0px
    }
    .chamberlain-content{
        font-size:14px;
        padding-bottom: 10px;
        line-height:24px
    }

</style>
<body>
    <div class="main">
        <div class="main-input">
            <p class="title-chamberlain"><?php echo $workItemInfo['detailDesc'];?></p>
            <?php if($workItemInfo['imageList']) { ?>
            <div style="padding:0px 0px 20px 0px">
                <div class="show-image-list" id="chose_pic_btn">
                    <?php foreach($workItemInfo['imageList'] as $imageInfo) { ?>
                    <div class="show-image">
                        <img src="<?php echo $imageInfo['imgUrl'];?>" />
                        <label><?php echo $imageInfo['id'];?></label>
                    </div>
                    <?php } ?>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <?php } ?>

            <?php if($workItemInfo['workItemOrderInfo']) { ?>
            <?php foreach ($workItemInfo['workItemOrderInfo'] as $oindex => $orderInfo) { ?>
                    <div style="padding: 10px 0px;">
                        <p><?php echo $oindex+1 .'、'. $orderInfo['detailDesc'];?></p>
                        <p>图片编号:<?php echo $orderInfo['imageIdStr'];?></p>
                    </div>
            <?php } ?>
            <?php } ?>
            <select>
                <option value="0">设置工单状态</option>
                <?php foreach($workItemStatus as $status => $statusVal) { ?>
                    <option value="<?php echo $status;?>"><?php echo $statusVal;?></option>
                <?php } ?>
            </select>

            <div>
                <div style="padding:10px 0px">需求描述:</div>
                <textarea id="workItemOrderContent" style="border:solid #ccc 1px;width: 100%;height:150px"></textarea>
                <div style="padding:10px 0px">图片Id:(多个用逗号分割)</div>
                <input id="imageInput" type="text" style="width:100%">
                <input type="hidden" value="<?php echo $workItemInfo['id'];?>" id="workItemId" />
                <div style="margin-top:10px; text-align: right">
                    <button type="button" class="btn btn-primary" id="j-submit-order">录入工单</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#j-submit-order').on('click', function () {
                var workItemOrderContent = $('#workItemOrderContent').val();
                var workItemId = $('#workItemId').val();
                var imageIdStr = $('#imageInput').val();
                $.ajax({
                    url: '/index/submitItemOrder',
                    data: {'workItemOrderContent': workItemOrderContent, 'imageIdStr': imageIdStr, 'workItemId':workItemId},
                    type: 'POST',
                    dataType: 'json',
                    success: function (responseData) {
                        if(responseData.code == 200) {
                            window.location.reload();
                        }
                    }
                })
            });

        })

    </script>
</body>
</html>