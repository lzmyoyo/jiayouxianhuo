<?php require APPPATH . 'views/public/wapheader.php'; ?>
<style>
    .mui-btn{
        padding-left:20px;
        padding-right:20px;
        border-radius:15px;
        margin-top: 10px;
        letter-spacing:1px;
    }
    .user-tag{
         position: relative
    }
    .user-text{
        line-height: 2.1rem;letter-spacing:1px;  position: absolute;top:60px;color: #fff; width:100%; text-align: center; font-weight: bold;
    }
    .user-welcome{
        font-size:1.4rem;
    }
    .mui-table-view-cell a label{
        margin-right: 10px;
    }
    .mui-popup-textView{
        font-size:14px
    }
    .mui-btn-danger, .mui-btn-negative, .mui-btn-red{

    }

</style>
<div class="mui-content">
    <div class="user-tag">
        <div class="user-text">
            <div class="user-welcome">欢迎你</div>
            <?php if($userInfo) { ?>
            <div style="color: #fff;"><?php echo $userInfo['userName'] ? $userInfo['userName'] : $userInfo['mobile']; ?></div>
            <?php } else { ?>
            <button type="button" class="mui-btn mui-btn-primary" id="j-to-login">登录 / 注册</button>
            <script>
                document.getElementById('j-to-login').addEventListener('tap',function () {
                    mui.openWindow({
                        url: '/mobile/user/login',
                    });
                });
            </script>
            <?php } ?>
        </div>
        <img src="/userbg.jpg" style="width: 100%" />
    </div>
    <ul class="mui-table-view mui-table-view-chevron">
        <li class="mui-table-view-cell user-login-action" id="j-edit-username">
            <a class="mui-navigate-right">
                <label class="mui-icon mui-icon-contact"></label>昵称 : <?php echo isset($userInfo['userName']) && $userInfo['userName'] ? $userInfo['userName'] : '未设置昵称';?>
            </a>
        </li>
        <li class="mui-table-view-cell user-login-action" id="j-user-order-list">
            <a class="mui-navigate-right">
                <label class="mui-icon mui-icon-location"></label>
                我的订单
            </a>
        </li>
        <li class="mui-table-view-cell user-login-action" id="j-user-addrsss-list">
            <a class="mui-navigate-right">
                <label class="mui-icon mui-icon-location"></label>
                我的收货地址
            </a>
        </li>
        <li class="mui-table-view-cell">
            <a class="mui-navigate-right" href="/mobile/user/about">
                <label class="mui-icon mui-icon-chatbubble"></label>
                一封介绍
            </a>
        </li>
        <li class="mui-table-view-cell user-login-action" id="j-speaking">
            <a class="mui-navigate-right">
                <label class="mui-icon mui-icon-compose"></label>
                有话说
            </a>
        </li>
    </ul>
    <?php if($userInfo) { ?>
    <ul class="mui-table-view mui-table-view-chevron" style="margin-top: 10px; text-align: center;">
        <li id="loginOut" class="mui-table-view-cell" style="width: 100%;padding-left:0px; padding-right: 0px; color: #8a6d3b; font-weight: bold">
            退出登录
        </li>
    </ul>
    <script>
        document.getElementById('loginOut').addEventListener('tap',function () {
            var btnArray = ['点错了', '决意退出'];
            mui.confirm('你确定要退出管家中心吗?', '退出提醒', btnArray, function(e) {
                if (e.index == 1) {
                    mui.openWindow({
                        url: '/mobile/user/loginOut',
                        id:'info'
                    });
                }
            })
        });
    </script>
    <?php } ?>
</div>
<script>
<?php if(!$userId) { ?>
    //tap为mui封装的单击事件，可参考手势事件章节
    var userLoginActionClass = document.getElementsByClassName('user-login-action');
    for(var i=0;i<userLoginActionClass.length;i++){
        userLoginActionClass[i].addEventListener('tap',function () {
            mui.alert('你需要先登录.', '请你先登录！', function() {
                //打开页面
                mui.openWindow({
                    url: '/mobile/user/login',
                });
            });
        });
    }
<?php } else { ?>

document.getElementById('j-speaking').addEventListener('tap',function () {
    var btnArray = ['取消', '确定'];
    mui.prompt('谢谢你对我们的支持', '一个要求,怎么着你也得找个优点给我们,谢谢', '意见和建议', btnArray, function (e) {
        if (e.index == 1) {
            var feedBackContent = e.value;
            mui.post('/mobile/user/addFeedBack',{
                feedBackContent:feedBackContent,
                },function(dataResult){
                    if(dataResult.code == 200) {
                        mui.alert('谢谢您的支持', '已收到反馈', function() {});
                    } else {
                        mui.alert(dataResult.message, '错误提醒', function() {});
                    }
                },'json'
            );


        } else {

        }
    });
});


document.getElementById('j-edit-username').addEventListener('tap',function () {
    var btnArray = ['取消', '确定'];
    mui.prompt('取名是个艺术活', '', '换个昵称', btnArray, function (e) {
        if (e.index == 1) {
            var userName = e.value;
            if(userName) {
                mui.post('/mobile/user/updateName',{
                        userName:userName,
                    },function(dataResult){
                        if(dataResult.code == 200) {
                            mui.alert('您好,' + userName, '昵称已更换！', function() {
                                //打开页面
                                mui.openWindow({
                                    url: '/mobile/user',
                                });
                            });
                        } else {
                            mui.alert(dataResult.message, '修改失败了！', function() {});
                        }
                    },'json'
                );
            } else {
                mui.alert('您好,不能接受无名氏!', '修改失败了！', function() {

                });
            }
        } else {

        }
    });
});

document.getElementById('j-user-addrsss-list').addEventListener('tap',function () {
    mui.openWindow({
        url: '/mobile/user/address',
    });
});
document.getElementById('j-user-order-list').addEventListener('tap',function () {
    mui.openWindow({
        url: '/mobile/index/orderList',
    });
});



<?php } ?>
</script>
<?php require APPPATH . 'views/public/wapfooder.php'; ?>
