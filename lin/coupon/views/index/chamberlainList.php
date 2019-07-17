<!DOCTYPE html>
<html dir="ltr" lang="en-US" class="js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>我的购物管家</title>
    <meta name="keywords" content="购物管家,我的管家,购物需求,我的购物管家">
    <meta name="description" content="我的购物管家,帮你解决各种购物烦恼,为你推荐值得购买商品。">
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="http://www.jq22.com/demo/jQueryViewer20160329/css/viewer.min.css" />
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="/jquery-1.10.2.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

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
        padding-bottom: 10px;
        line-height:24px
    }
    .my-qus{
        padding:10px 0px;

    }
    .my-qus .my-qus-title{
        line-height:28px;
        padding: 0px;
        margin: 0px;
    }
    .my-qus .my-qus-content{
        line-height: 24px;
        font-size: 14px;
        margin-top:10px;
        color: #333;
    }
    .action-list{
        margin-top:10px
    }
    .action-list a{
        display: inline-block;
        line-height:30px;
        color:#999;
        margin-right:20px

    }
    .action-list a:hover{
        text-decoration: none;
    }
    .action-list a label{
        font-weight: normal;

    }
    .link-detail{
        display: inline-block;
        text-decoration: none;
    }
    .link-detail:hover{
        text-decoration: none;
    }
    .history-add:hover{
        text-decoration: none;
    }

</style>
<body>
    <div class="main">

        <?php include 'top-menu.php';?>

        <ul id="myTab" class="nav nav-tabs">
            <li class="active">
                <a href="#list" data-toggle="tab">
                    我的购物需求
                </a>
            </li>
            <li><a href="#history" data-toggle="tab">历史购物需求</a></li>
            <!--
                        <li class="dropdown">
                            <a href="#" id="myTabDrop1" class="dropdown-toggle"
                               data-toggle="dropdown">其它
                                <b class="caret"></b>
                            </a>

                            <ul class="dropdown-menu" role="menu" aria-labelledby="myTabDrop1">
                                <li><a href="#jmeter" tabindex="-1" data-toggle="tab">jmeter</a></li>
                                <li><a href="#ejb" tabindex="-1" data-toggle="tab">ejb</a></li>
                            </ul>
                        </li>
                        -->
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="list">


                <?php if($workItemList) { ?>
                    <?php foreach($workItemList as $workItem) { ?>
                    <div class="my-qus">
                        <a href="/index/chamberlainDetail/<?php echo $workItem['id'];?>" class="link-detail">
                            <h4 class="my-qus-title"><?php echo $workItem['detailDesc'];?></h4>
                            <div class="my-qus-content">
                                <p>
                                    <?php echo $workItem['showText'];?>
                                </p>
                            </div>
                        </a>
                        <div class="action-list">
                            <a href="javascript:;">
                                <span class="glyphicon glyphicon-time"></span>
                                <label><?php echo $workItem['addTimeStr'];?></label>
                            </a>
                            <a href="javascript:;">
                                <span class="glyphicon glyphicon-question-sign"></span>
                                <label><?php echo $workItem['statusText'];?></label>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>

                <div style="font-size:24px; line-height:48px">
                    <p style="padding:20px 0px 10px 0px">
                        <?php if(!$userId) { ?>
                            未检测到账户，您需要先<a class="login-button" href="javascript:;"> 登录 </a>
                        <?php } else { ?>
                            您还没有服务需求
                        <?php } ?>
                    </p>
                    <a class="history-add" href="/index/chamberlain">
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        <label>找我的管家</label>
                    </a>
                </div>

                <?php } ?>


            </div>
            <div class="tab-pane fade" id="history">
                <div style="font-size:24px; line-height:48px">
                    <p style="padding:20px 0px 10px 0px">
                        没有历史服务需求。
                    </p>
                    <a class="history-add" href="/index/chamberlain">
                        <span class="glyphicon glyphicon-arrow-right"></span>
                        <label>找我的管家</label>
                    </a>
                </div>

            </div>
        </div>


    </div>

    <?php include 'login-module.php';?>
    <script type="application/javascript" src="http://www.jq22.com/demo/jQueryViewer20160329/js/viewer.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.login-button').on('click',function () {
                $('#login').modal({});
            });
        });
    </script>
</body>
</html>