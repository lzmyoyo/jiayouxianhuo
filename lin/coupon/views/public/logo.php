<header id="top" role="banner" class="site-header js-site-header">
    <div class="site-header-content">
        <div class="site-header-main ">
            <a  href="/">
                <div class="logo">
                    <img style="width:220px" src="/Public/logo.png"/>
                </div>
            </a>
            <div class="full-search-toggle search-form js-search-form search-open" style="width:720px">
                <form class="form-search" method="get" style="height: 42px">
                    <svg class="icon icon-search">
                        <use xlink:href="#icon-search"></use>
                    </svg>
                    <input class="query js-search-query js-search-panel-opener" name="keywords" type="search"
                           placeholder="Search For Coupons" aria-label="Search" autocomplete="off"
                           maxlength="100" value="<?php echo $keyWords; ?>"></form>
            </div>
        </div>
    </div>
</header>