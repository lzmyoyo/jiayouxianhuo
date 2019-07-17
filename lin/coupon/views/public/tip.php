<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <title><?php echo $title; ?></title>
    <?php foreach ($style['css'] as $cssInfo) { ?>
        <link type="text/css" rel="stylesheet" href="<?php echo utils::getUrl() . $cssInfo; ?>"/>
    <?php } ?>
    <?php foreach ($style['js'] as $jsInfo) { ?>
        <script type="text/javascript" src="<?php echo utils::getUrl() . $jsInfo; ?>"></script>
    <?php } ?>
</head>
<div class="errormain">
    <ul>
        <li class="errortitle">操作提示</li>
        <li class="errorcontent"><?php echo $title; ?><br/>
            页面将在<span id="second"><?php echo $timeout; ?></span>跳转，要是未能跳转请按下面链接地址。
        </li>
        <li class="errorurl">
            <a href="<?php echo $backurl; ?>"><?php echo $backurl; ?></a>
        </li>
    </ul>
</div>
</body>
<script type="text/javascript">
    var url = '<?php echo $backurl;?>';
    var i = '<?php echo $timeout;?>';
</script>
<script type="text/javascript">
    $(function () {
        setTimeout(locationhref, 1000);
    });
    function locationhref() {
        i = i - 1;
        if (i == 0) {
            $('.errorcontent').html('页面加载中....请稍后。');
            $('.errorurl').hide();
            window.location.href = url;
        } else if (i > 1 || i == 1) {
            $('#second').html(i);
            setTimeout(locationhref, 1000);
        }
    }
</script>
</html>
