<style>
    .send-sms-button{
        position: absolute;
        right:15px;
        padding:0px 20px;
        top:40px;
        height:34px

    }
</style>
<!-- 注册窗口 -->
<div id="register" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                    </button>
                </div>
            <div class="modal-title">
                <h2 class="text-center">注册</h2>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="">手机号码</label>
                        <input class="form-control phone-num" id="register-phone" type="tel" placeholder="请输入你的手机号码">
                        <button class="send-sms-button j-send-message" data-type="register">免费获取验证码</button>
                    </div>
                    <div class="form-group">
                        <label for="">手机验证码</label>
                        <input class="form-control" maxlength="6" id="register-phonecode" type="text" placeholder="请输入6位手机验证码">
                    </div>
                    <div class="form-group">
                        <label for="">密码</label>
                        <input class="form-control" id="register-password" type="password" placeholder="为了安全,请尽量使用复杂的密码">
                    </div>
                    <div id="registerErrorMessage" class="alert alert-warning" style="display: none;">
                        <a href="#" class="close closeRegisterErrorMessage" aria-hidden="true">&times;</a>
                        <div class="messageData"></div>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-primary" id="ajax-register">提交注册</button>
                    </div>
                    <a href="javascript:;" data-toggle="modal" data-dismiss="modal" data-target="#login">已有账号？点我登录</a>
                </div>
            </div>
        </div>
    </div>

<!-- 登录窗口 -->
<div id="login" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                    </button>
                </div>
            <div class="modal-title">
                <h1 class="text-center">登录</h1>
                </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">手机号码</label>
                    <input class="form-control" id="login-password-phone" type="text" placeholder="请输入你的手机号码">
                    </div>
                <div class="form-group">
                    <label for="">密码</label>
                    <input class="form-control" id="login-password" type="password" placeholder="请输入你的账户密码">
                </div>
                <div id="passwrodLoginErrorMessage" class="alert alert-warning" style="display: none;">
                    <a href="#" class="close closePasswordErrorMessage" aria-hidden="true">&times;</a>
                    <div class="messageData"></div>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary" id="ajax-login-password" type="submit">立即登录</button>
                </div>
                <a href="" data-toggle="modal" data-dismiss="modal" data-target="#register">还没有账号？点我注册</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="" data-toggle="modal" data-dismiss="modal" data-target="#loginSms">短信验证码登录</a>
                </div>
            </div>
        </div>
    </div>

<!-- SMS登录窗口 -->
<div id="loginSms" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-title">
                <h1 class="text-center">验证码登录</h1>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">手机号码</label>
                    <input class="form-control phone-num" id="login-sms-phone" type="text" placeholder="请输入你的手机号码">
                    <button class="send-sms-button j-send-message" data-type="login">免费获取验证码</button>
                </div>
                <div class="form-group">
                    <label for="">手机验证码</label>
                    <input class="form-control" maxlength="6" type="text" id="login-sms-phonecode" placeholder="请输入6位手机验证码">
                </div>
                <div id="smsLoginErrorMessage" class="alert alert-warning" style="display: none;">
                    <a href="#" class="close closeSsmsErrorMessage" aria-hidden="true">&times;</a>
                    <div class="messageData"></div>
                </div>
                <div class="text-right">
                    <button class="btn btn-primary" type="submit" id="ajax-login-sms">立即登录</button>
                </div>
                <a href="" data-toggle="modal" data-dismiss="modal" data-target="#register">还没有账号？点我注册</a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="" data-toggle="modal" data-dismiss="modal" data-target="#login">账户密码登录</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#ajax-login-sms').on('click', function () {
            var phoneNum = $('#login-sms-phone').val();
            var phoneNumCode = $('#login-sms-phonecode').val();
            $.ajax({
                url: '/index/smsLogin',
                data: {'phoneNum': phoneNum, 'phoneNumCode': phoneNumCode},
                type: 'POST',
                dataType: 'json',
                success: function (responseData) {

                    if(responseData.code == 400) {
                        var messageData = '';
                        $.each(responseData.data,function (key,message) {
                            messageData += message + '<br />'
                        });
                        $('#smsLoginErrorMessage').show();
                        $('#smsLoginErrorMessage .messageData').html(messageData);
                    } else {
                        window.location.reload();
                    }
                }
            })
        });
        $('#ajax-login-password').on('click', function () {
            var phoneNum = $('#login-password-phone').val();
            var password = $('#login-password').val();
            $.ajax({
                url: '/index/passwordLogin',
                data: {'phoneNum': phoneNum, 'password': password},
                type: 'POST',
                dataType: 'json',
                success: function (responseData) {
                    if(responseData.code == 400) {
                        var messageData = '';
                        $.each(responseData.data,function (key,message) {
                            messageData += message + '<br />'
                        });
                        $('#passwrodLoginErrorMessage').show();
                        $('#passwrodLoginErrorMessage .messageData').html(messageData);
                    } else {
                        window.location.reload();
                    }
                }
            })
        });
        $('#ajax-register').on('click', function () {
            $('#registerErrorMessage').hide();
            var phoneNum = $('#register-phone').val();
            var phoneNumCode = $('#register-phonecode').val();
            var password = $('#register-password').val();
            $.ajax({
                url: '/index/register',
                data: {'phoneNum': phoneNum, 'phoneNumCode': phoneNumCode, 'password': password},
                type: 'POST',
                dataType: 'json',
                success: function (responseData) {
                    if(responseData.code == 400) {
                        var messageData = '';
                        $.each(responseData.data,function (key,message) {
                            messageData += message + '<br />'
                        });
                        $('#registerErrorMessage').show();
                        $('#registerErrorMessage .messageData').html(messageData);
                    } else {
                        window.location.reload();
                    }

                }
            })
        });

        $('.closeRegisterErrorMessage').on('click',function(){
            $('#registerErrorMessage').hide();
        });
        $('.closePasswordErrorMessage').on('click',function(){
            $('#passwrodLoginErrorMessage').hide();
        });
        $('.closeSmsErrorMessage').on('click',function(){
            $('#smsLoginErrorMessage').hide();
        });
        $('.j-send-message').on('click',function () {
            var sendType = $(this).data('type');
            var phoneNum = $(this).siblings('.phone-num').val();
            var _this = $(this);
            $.ajax({
                url: '/index/sendMessage',
                data: {'phoneNum': phoneNum, 'sendType': sendType},
                type: 'POST',
                dataType: 'json',
                success: function (responseData) {
                    if(responseData.code == 200) {
                        settime(_this);
                    }
                }
            })
        });

        var countdown=60;
        function settime(obj) { //发送验证码倒计时
            if (countdown == 0) {
                obj.attr('disabled',false);
                //obj.removeattr("disabled");
                obj.text("免费获取验证码");
                countdown = 60;
                return;
            } else {
                obj.attr('disabled',true);
                obj.text("重新发送(" + countdown + ")");
                countdown--;
            }
            setTimeout(function() {
                    settime(obj) }
                ,1000)
        }

    })
</script>