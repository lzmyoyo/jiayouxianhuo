<?php if ($couponList) { ?>
    <div class="fluid js-no-right-rail results">

        <div class="js-offer-content">
            <div class="js-offer-loading offer-loading-overlay"></div>
            <ul class="offer-list js-offers" data-impressions="">
                <div id="offer-list">
					<?php foreach ($couponList as $couponInfo) { ?>
                        <li class="offer-list-item">
                            <div class="offer-item js-offer ">
                                <div class="offer-item-content">
                                    <div class="offer-anchor-merchant"><a
                                                href="<?php echo $couponInfo['brandInfo']['brandSiteUrl']; ?>"
                                                class="offer-anchor-merchant-logo" target="" style="display:inline-block"><img
                                                    class="" style="display: inline-block; vertical-align: middle;"
                                                    src="<?php echo $couponInfo['brandInfo']['brandLogo']; ?>"></a>
                                    </div>
                                    <div class="offer-item-main">
                                        <div class="offer-item-head">
                                            <div class="offer-item-label has-separator-dot offer-type-code">
												<?php echo $couponInfo['couponTypeName'];?>
                                            </div>
                                            <div class="offer-merchant-name has-separator-dot"><?php echo $couponInfo['brandInfo']['brandName']; ?></div>
                                            <!--
                                            <div class="js-save-offer save-offer ">
                                                <svg class="icon icon-star-outline">
                                                    <use xlink:href="#icon-star-outline"></use>
                                                </svg>
                                                <span class="save-offer-copy js-save-offer-copy">Save</span>
                                            </div> -->
                                        </div>
                                        <div class="offer-item-body">
                                            <div class="offer-item-body-content">
                                                <div class="offer-item-title"><a
                                                            href="<?php echo $couponInfo['couponSiteUrl'];?>"
                                                            class="offer-item-title-link js-triggers-outclick"><?php echo $couponInfo['couponName']; ?></a>
                                                </div>
                                                <div class="offer-item-info">
                                                    <div class="offer-meta offer-meta-usage has-separator-dot">
														<?php echo round($couponInfo['useNum']/1000,2);?>k uses today
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="offer-item-actions">
												<?php if($couponInfo['saleType'] == 1) { ?>
                                                    <a href="<?php echo $couponInfo['couponSiteUrl'];?>"
                                                       class="button-show-code offer-button">Show
                                                        Code</a>
												<?php } else { ?>
                                                    <a href="<?php echo $couponInfo['couponCode'];?>"
                                                       class="button-primary offer-button">Get Deal</a>
												<?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="js-offer-item-details offer-item-details">
                                    <div class="offer-item-details-bar">
                                        <a class="js-offer-item-details-link offer-item-details-link"
                                           data-id="<?php echo $couponInfo['id']; ?>">
                                            See Details
                                            <svg class="icon icon-arrow-down"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-arrow-down"></use></svg>
                                        </a>
                                    </div>
                                    <div class="js-offer-item-tabs offer-item-tabs"
                                         style="display: none"
                                         id="details-<?php echo $couponInfo['id']; ?>">
                                        <div class="offer-item-tabs-content" style="display:block">
                                            <div class="offer-item-tabs-content-item js-tabs-content-item offer-item-tabs-content-exclusions"
                                                 style="display:block">
												<?php if($couponInfo['couponEndTime'] > 0) { ?>
                                                    <p>
                                                        <strong>Expires:</strong>&nbsp;<?php echo date('Y-m-d H:i:s', $couponInfo['couponEndTime']); ?>
                                                    </p>
												<?php } ?>

                                                <p>
                                                    <strong>Exclusions:</strong>&nbsp;
													<?php echo $couponInfo['couponDesc']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
					<?php } ?>
                </div>
            </ul>
        </div>

    </div>
<?php } ?>