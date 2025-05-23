<div v-if="appMainFilter.mob.nav_filter.show" class="mobileFilterMain">
    <div class="mobileFilterOverlay" style="color: #fff;">
        <div class="mobileFilterContent" style="position: relative">
            <div class="mobileFilterContentHeader" style="padding: 18px 10px;">
                <div class="mobileSearchContentHeaderWrapper">
                    <div class="mobileSearchContentHeaderSearch">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                    fill="white"/>
                        </svg>
                    </div>
                    <div class="mobileMenuContentHeading" style="">
                        <div class="mobileMenuContentHeadingTitle"
                             style="font-family: 'Sofia Sans',sans-serif;font-weight: 600;font-size: 20px;line-height: 125%;letter-spacing: 0%;text-align: center;">
                            Фильтры
                        </div>
                    </div>
                    <div class="mobileSearchContentHeaderExit" @click="toggleMobFilter">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                    fill="#F9F9F9"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mobileFilterContentWrapper" style="">
                <?php echo do_shortcode('[woocommerce_filter]') ?>
            </div>
            <div class="mobileFilterContentFooter" style="position: absolute; bottom: 0; right: 0; left: 0; background: #fff;">
                <div class="filterContentFooterBlock" style="padding: 8px 10px; margin-bottom: 30px; width: 100%;">
                    <div class="footerBlockBtn" style="background: #F0C224; border-radius: 100px; width: 100%; padding: 12px 24px;">
                        <div class="footerBlockBtnTitle" style="font-family: 'Montserrat',sans-serif;font-weight: 600;font-size: 14px;line-height: 125%;letter-spacing: 0%;text-align: center; color: #000;" @click="toggleMobFilter">
                            Показать товары
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>