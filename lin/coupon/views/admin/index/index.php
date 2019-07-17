<!DOCTYPE html>
<html style="background: #49494b; ">
<head>
    <meta charset="UTF-8">
    <title>宅乐轩ERP管理系统</title>
</head>
<frameset rows="130,*" frameborder="0">
    <frame name="top" src="<?php echo utils::getUrl('admin/index/top'); ?>" frameborder="0" id="topFrame" scrolling="no"
           noresize>
    <frameset cols="73,*" frameborder="0">
        <frame name="left" src="<?php echo utils::getUrl('admin/index/left'); ?>" id="leftFrame" frameborder="0"
               scrolling="no" noresize>
        <frame name="main" src="<?php echo utils::getUrl('admin/index/main'); ?>" id="mainFrame" frameborder="0"
               scrolling="yes" noresize>
    </frameset>
</frameset>
</html>