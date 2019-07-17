<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>添加我的购物需求</title>
    <meta name="keywords" content="购物管家,我的管家,购物需求,我的购物管家">
    <meta name="description" content="我的购物管家,帮你解决各种购物烦恼,为你推荐值得购买商品,添加我的购物需求。">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
        width:930px;
        margin: 0px auto;
    }
    .main-title{
        padding:10px 0px
    }

    .main-input {
        border:solid #ccc 1px;
    }
    .main-input textarea{
        box-shadow:none;
        border:0;
        outline:none;
        height: 320px;
    }
    .guanjia-detial{
        border-top: 1px solid #ccc;
    }
    .guanjia-detial p{
        padding:10px;
        font-size:14px;
        font-weight: bold;
    }
    .guanjia-detial ul{
        padding:0px 10px 10px 10px;
    }
    .guanjia-detial ul li{
        padding-left:20px;
        line-height: 24px;
    }
    .show-image{
        height:100px;width:100px; border:solid #ccc 1px;display: inline-block;text-align: center;
        float: left;padding:5px;
        border: solid #ccc 1px;;
        margin-right: 10px;
        position: relative;
    }
    .show-image span{
        position: absolute;
        display: inline-block;
        width:20px;
        height:20px;
        background-color: #1a1f21;
        border-radius:50%;
        text-align: center;
        line-height: 20px;
        color:#fff;
        font-size:12px;
        top:-8px;
        right:-8px
    }
    .show-image:after{
        clear: both;
    }
    .show-image img{
        height: 100%;
        max-width: 100%;
    }
    .show-image-list{
        float: left;
    }
    .show-image-list div:last-child{
        clear: both;
    }
    .add-image{
        height:100px;width:100px; border:solid #ccc 1px;background: #eee; font-size:60px;line-height: 90px; text-align: center;color: #ccc;
        float: left;
        position: relative;
        overflow: hidden;
        z-index: 2;
    }
    .add-image input{
        position: absolute;
        left:0px;
        top:0px;
        width:100%;
        height:100%;
        opacity:0
    }

</style>
<body>
    <div class="main">
        <?php include 'top-menu.php';?>
        <div class="main-title">
            <h5>填写我的购物需求</h5>
        </div>
        <div class="main-input">
            <textarea id="inputFormConteol" class="form-control" rows="10" style="resize: none;" placeholder="管家，帮我选一件毛衣，圆领的，羊毛保暖一点，价格不要超过200元。顺便帮我找条围巾，长的那种。" ></textarea>
            <div style="padding:10px 0px 10px 10px">
                <div class="show-image-list" id="chose_pic_btn">
                </div>
                <div class="add-image">
                    +
                    <input type="file" accept="image/*" value="" />
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div style="text-align: right; margin-top: 20px;">
            <button type="button" class="btn btn-primary" id="j-submit-work">提交我的购物需求</button>
        </div>
    </div>
    <?php include 'login-module.php';?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        var picArr = new Array();// 存储图片
        var uploadPicArr = new Array();
        $('input:file').localResizeIMG({
            width:800,// 宽度
            quality: 0.8, // 压缩参数 1 不压缩 越小清晰度越低
            success: function (result) {
                var img = new Image();
                img.src = result.base64;
                $.ajax({
                    url:'/index/base64Upload',
                    data:{'base64Image':result.base64},
                    type:'POST',
                    dataType:'json',
                    success:function (responseData) {
                        if(responseData.code == 200) {
                            var _str = '<div class="show-image"><img src="'+img.src+'" /><span class="delete_pic">X</span></div>';
                            $('#chose_pic_btn').before(_str);
                            var _i =  picArr.length;
                            var uploadImagePath = responseData.data.image;
                            uploadPicArr[_i] = uploadImagePath;
                            picArr[_i] = result.base64;
                        } else {
                            alert('图片上次失败');
                        }
                    }
                })




            }
        });
        // 删除
        $(document).on('click', '.delete_pic', function(event) {
            var aa = $(this).parents(".show-image").index();
            picArr.splice(aa,1);
            uploadPicArr.splice(aa,1);
            $(this).parents(".show-image").remove();
        });


        $(document).ready(function(){
            <?php if(!$userId) { ?>
            <?php if($userIsLogined) { ?>
            $('#login').modal({});
            $('#inputFormConteol').on('focus',function () {
                $('#login').modal({});
            });
            <?php } else { ?>
            $('#register').modal({});
            $('#inputFormConteol').on('focus',function () {
                $('#register').modal({});
            });
            <?php } ?>
            <?php } ?>



            $('#j-submit-work').on('click',function () {
                var workItemContent = $('#inputFormConteol').val();
                var base64ImageList = uploadPicArr;
                $.ajax({
                    url:'/index/submitWorkItem',
                    data:{'workItemContent':workItemContent,'base64ImageList':base64ImageList},
                    type:'POST',
                    dataType:'json',
                    success:function (responseData) {
                        if(responseData.code == 200) {
                            window.location.href = '/index/chamberlainList';
                        }
                    }
                })


            })



        });


    </script>
</body>
</html>