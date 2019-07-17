<?php require APPPATH . 'views/public/header.php'; ?>
<style>
    .offer-item-tabs-content-details, .offer-item-tabs-content-exclusions {
        height: auto;
    }
    .grid-fixed-fluid .fluid{
        padding-left: 0px;
    }
</style>
<div class="overflow-target js-overflow-target">
    <div class="main-content">
		<?php  require APPPATH . 'views/public/logo.php';?>
        <div role="main" id="site-main" class="js-site-main">
			<?php  require APPPATH . 'views/public/menu.php';?>
            <div class="container js-page-container " id="search-offer-list">
                <div class="grid-fixed-fluid search-content-grid">
					<?php  require APPPATH . 'views/index/couponList.php';?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <svg xmlns="http://www.w3.org/2000/svg">
        <symbol viewBox="0 0 24 24" id="icon-close"><title>close</title>
            <path d="M19.3 5.4L5.4 19.3c-.2.2-.6.3-.8.1-.2-.2-.2-.5.1-.8L18.6 4.7c.2-.2.6-.3.8-.1.2.2.1.6-.1.8"></path>
            <path d="M18.6 19.3L4.7 5.4c-.2-.2-.3-.6-.1-.8s.5-.2.8.1l13.9 13.9c.2.2.3.6.1.8-.2.2-.6.1-.8-.1"></path>
        </symbol>
        <symbol viewBox="0 0 16 16" id="icon-envelope"><title>envelope</title>
            <path class="cls-1"
                  d="M14.33 2H1.67A1.67 1.67 0 0 0 0 3.67v8.67A1.67 1.67 0 0 0 1.67 14h12.66A1.67 1.67 0 0 0 16 12.33V3.67A1.67 1.67 0 0 0 14.33 2zm0 1.33h.07L8 9.75l-6.4-6.4h12.73zm0 9.33H1.67a.33.33 0 0 1-.33-.33V5l6.2 6.2a.67.67 0 0 0 .94 0l6.2-6.2v7.36a.33.33 0 0 1-.35.3z"></path>
        </symbol>
        <symbol viewBox="0 0 16 16" id="icon-phone"><title>phone</title>
            <path class="cls-1"
                  d="M11.33 0H4.67A1.67 1.67 0 0 0 3 1.67v12.66A1.67 1.67 0 0 0 4.67 16h6.67A1.67 1.67 0 0 0 13 14.33V1.67A1.67 1.67 0 0 0 11.33 0zm-7 4h7.33v6H4.33zm0-2.33a.33.33 0 0 1 .33-.33h6.67a.33.33 0 0 1 .33.33v1H4.33zm7 13H4.67a.33.33 0 0 1-.33-.33v-3h7.33v3a.33.33 0 0 1-.34.33z"></path>
            <circle class="cls-1" cx="7.67" cy="13" r="1" transform="rotate(-1.46 7.68 13.05)"></circle>
        </symbol>
        <symbol viewBox="0 0 16 16" id="icon-star-outline"><title>star-outline</title>
            <path class="icon-outline" fill="currentColor"
                  d="M12.53 16a.67.67 0 0 1-.32-.08L8 13.59l-4.21 2.33a.67.67 0 0 1-1-.69l.81-5-3.41-3.5a.67.67 0 0 1 .38-1.12l4.72-.72L7.4.38a.67.67 0 0 1 1.2 0l2.12 4.51 4.72.72a.67.67 0 0 1 .38 1.12l-3.43 3.52.81 5a.67.67 0 0 1-.66.77z"></path>
            <path class="icon-fill"
                  d="M8 12.16a.67.67 0 0 1 .32.08l3.33 1.84-.64-4a.67.67 0 0 1 .18-.57L14 6.73l-3.78-.58a.67.67 0 0 1-.5-.38L8 2.24 6.34 5.78a.67.67 0 0 1-.5.38L2 6.73l2.81 2.83a.67.67 0 0 1 .18.57l-.64 4 3.33-1.84a.67.67 0 0 1 .32-.13z"></path>
        </symbol>
        <symbol viewBox="0 0 16 16" id="icon-search"><title>search</title><path d="M15.9 15.1l-3.6-3.6C13.4 10.4 14 8.8 14 7c0-3.9-3.1-7-7-7S0 3.1 0 7s3.1 7 7 7c1.8 0 3.4-.6 4.6-1.7l3.6 3.6c.2.2.5.2.7 0 .1-.2.1-.6 0-.8zM7 13c-3.3 0-6-2.7-6-6s2.7-6 6-6 6 2.7 6 6-2.7 6-6 6z"/></symbol>
        <symbol viewBox="0 0 16 16" id="icon-arrow-down"><title>arrow-down</title><path class="cls-1" d="M15.7 4.3a1 1 0 0 0-1.4 0L8 9.6 1.75 4.3a1 1 0 0 0-1.42 0 .94.94 0 0 0 0 1.36l7 6a1 1 0 0 0 1.42 0l7-6a.94.94 0 0 0-.04-1.33z"/></symbol>
    </svg>
</div>
<?php require APPPATH . 'views/public/fooder.php'; ?>

