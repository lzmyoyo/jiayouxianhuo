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
<style type="text/css">
    body{margin:0px auto;}
    .main{width:1280px; margin:0px auto;}
    .table_list{overflow:auto; background-color:#f8f8f8; padding-top:8px; padding-bottom:8px}
    a{text-decoration:none; display:inline-block; }
    .table_list a{ width:300px; float:left; height:24px; line-height:24px; overflow:hidden; padding-left:15px;}
    table {
        border-collapse:collapse;
        border:none;
        width:100%;
        margin-top:20px;
    }
    td,th{
        border:solid #e2ecec 1px;
        height:30px;
        text-align:center;
    }
    .fields{ text-align:left; padding-left:15px}
    .table_name{text-align:left; padding-left:20px}
</style>
<body>
    <div class="main">
        <table>
            <tr style="background-color:#f5fbfb" onmouseover="bg(this);" onmouseout="obg(this)">
                <td>ID</td>
                <td>描述</td>
                <td width="180">新增时间</td>
                <td width="180">编辑时间</td>
                <td width="120">状态</td>
                <td width="140">操作</td>
            </tr>
            <?php foreach($workItemList as $vkey=>$workItemInfo){?>
                <tr <?php if($vkey%2){echo 'style="background-color:#f5fbfb"';}?> onmouseover="bg(this);" onmouseout="obg(this)">
                    <td><?php echo $workItemInfo['id'];?></td>
                    <td class="fields"><?php echo $workItemInfo['detailDesc'];?></td>
                    <td><?php echo date('Y-m-d H:i:s',$workItemInfo['addTime']);?></td>
                    <td><?php echo date('Y-m-d H:i:s',$workItemInfo['updateTime']);?></td>
                    <td><?php echo $workItemStatus[$workItemInfo['status']];?></td>
                    <td>
                        <?php if($workItemInfo['status'] == 1) { ?>
                            <a href="/index/workItemToOrder/<?php echo $workItemInfo['id'];?>">拆解任务</a>
                        <?php } ?>
                        <a href="/index/workItemOrderList?workItemId=<?php echo $workItemInfo['id'];?>">查看详情</a>

                    </td>
                </tr>
            <?php } ?>
        </table>

    </div>
    <!--js鼠标移动到指定行事件-->
    <script type="text/javascript">
        var bgcolor = '';
        function bg(trthis){
            bgcolor =  trthis.style.backgroundColor;
            trthis.style.backgroundColor = '#c6dfdb';
        }
        function obg(trthis){
            trthis.style.backgroundColor = bgcolor;
        }
    </script>
</body>
</html>