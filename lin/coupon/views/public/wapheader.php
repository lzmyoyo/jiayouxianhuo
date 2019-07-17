<html lang="en">
<head>
    <title><?php echo $title;?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <script src="/js/mui.min.js"></script>
    <link href="/css/mui.min.css" rel="stylesheet"/>
    <script type="text/javascript" charset="utf-8">
        mui.init();
    </script>
    <style>
        .mui-bar-nav a{
            font-weight: 700;
        }
        .nav-bootom-bar{
            font-weight: 700;
        }
        .mui-card-content{
            font-size: 15px;
        }
        p{
            font-size:15px;
            line-height: 25px;
        }
    </style>
</head>
<body>
    <?php if($isShowHeader) { ?>
    <header class="mui-bar mui-bar-nav">
        <?php if($headerMenu['back']) {?>
            <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <?php } ?>
        <?php if($headerMenu['more']) {?>
        <a class="mui-icon mui-icon-more mui-pull-left" id="j-work-other"></a>
        <script>
            document.getElementById('j-work-other').addEventListener('tap',function () {
                mui.openWindow({
                    url: '/mobile/index/other',
                    id:'info'
                });
            });
        </script>
        <?php } ?>
        <h1 class="mui-title"><?php echo $title;?></h1>
        <?php if($headerMenu['add']) {?>
        <a class="mui-icon mui-icon-plusempty mui-pull-right add-workItem"></a>
        <?php } ?>
        <?php if($headerMenu['addImage']) {?>
            <a class="mui-icon mui-icon-image mui-pull-right">
                <input id="j-addImage" style="display: inline; position: absolute;top: 0px;left:0px;height: 100%; opacity: 0" type="file" accept="image/*"/>
            </a>
        <?php } ?>
        <?php if($headerMenu['showChat']) {?>
            <a class="mui-icon mui-icon-help mui-pull-right" id="showChat"></a>
        <?php } ?>
        </header>
    <?php }  ?>
    <?php if($isShowFooder) { ?>
    <nav class="mui-bar mui-bar-tab">
        <a class="mui-tab-item <?php echo $menuActive['itemActive'];?> j-work-list" href="javascript:;">
<!--            <span class="mui-icon mui-icon-bars nav-bootom-bar"><span class="mui-badge">9</span></span>-->
            <span class="mui-icon mui-icon-bars nav-bootom-bar"></span>
            <span class="mui-tab-label">采购需求</span>
        </a>
        <a class="mui-tab-item all-cart <?php echo $menuActive['cartActive'];?>" href="javascript:;">
            <span class="mui-icon mui-icon-gear nav-bootom-cart"></span>
            <span class="mui-tab-label">采购单</span>
        </a>
        <a class="mui-tab-item add-workItem <?php echo $menuActive['addItemActive'];?>" href="javascript:;">
            <span class="mui-icon mui-icon-plusempty nav-bootom-bar"></span>
            <span class="mui-tab-label">找管家</span>
        </a>
        <a class="mui-tab-item j-user-me <?php echo $menuActive['meActive'];?>" href="javascript:;">
            <span class="mui-icon mui-icon-contact nav-bootom-bar"></span>
            <span class="mui-tab-label">我</span>
        </a>
    </nav>
    <?php } ?>

