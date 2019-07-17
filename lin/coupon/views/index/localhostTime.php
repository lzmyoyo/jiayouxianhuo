<?php require APPPATH . 'views/public/header.php'; ?>
<div id="backUrl" style="display: none"><?php echo base64_decode($backUrl);?></div>
<?php require APPPATH . 'views/public/fooder.php'; ?>
<script>
    $(document).ready(function(){
        var diffTimeMin = new Date().getTimezoneOffset();
        var backUrl = $('#backUrl').html();
        $.ajax('/index/setTimeDiff',{
            type:'get',
            data:{'diffTime':diffTimeMin},
            dataType:'json',
            success:function(data){
                if(data.code == 200) {
                    window.location.href = backUrl;
                }
            },
        });
    });
</script>
