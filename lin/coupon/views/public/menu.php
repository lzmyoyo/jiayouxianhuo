<div class="page-header-fullwidth js-page-header-fullwidth">
    <div class="js-category-header category-header has-hero-image">
        <div class="category-header-inner">
            <div class="container">
                <div class="category-header-content">
                    <div class="category-title-wrapper">
                        <a href="/" <?php if($isHomeCurrent) { echo 'class="current"';} ?>>Home</a>
                        <a href="/filter/discountamount/default" <?php if($filterList['discountamount']['ischeck']) { echo 'class="current"';} ?>>Percent Off</a>
                        <a href="/filter/discountrate/default" <?php if($filterList['discountrate']['ischeck']) { echo 'class="current"';} ?>>Dollar Off</a>
                        <a href="/filter/buy/default" <?php if($filterList['buy']['ischeck']) { echo 'class="current"';} ?>>Buy One Get One</a>
                        <a href="/filter/free/default" <?php if($filterList['free']['ischeck']) { echo 'class="current"';} ?>>Free Shipping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>