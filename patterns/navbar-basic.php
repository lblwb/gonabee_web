<?php

/**
 * Title: Navbar Basic
 * Slug: ktsportwear/navbar-basic
 * Categories: banner
 * @package WordPress
 */
?>

<!-- wp:group -->
<div class="headerMain" style="color: #fff; transition: height 0.3s ease;">
    <!-- wp:pattern {"slug":"ktsportwear/topnavbar-basic"} /-->
    <!-- wp:group -->
    <div class="headerMainNav wp-block" id="headerMainNav" v-cloak
        data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
        data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
        data-count="<?php $wc_cart = WC()->cart;
                    echo count($wc_cart->get_cart()) ?>">
        <div class="headerMbBarNavLogo" :class="{__Hide: !appMainNav.mob.nav_menu.logo.show}">
            <div class="headerMbBarNavLogoWrapper" style="display: flex; justify-content: center">
                <a href="/" style="display: flex">
                    <img style="width: 100%; max-width: 140px;"
                        src="<?php echo get_stylesheet_directory_uri() . '/assets/images/basic_logo.svg'; ?>">
                </a>
            </div>
        </div>
        <!-- wp:group -->
        <div class="headerMainNavWrap gridWrap wp-block-group"
            style="display: flex;align-items: center;padding: 12px 0;justify-content: space-between;">

            <?php get_template_part("inc/mobile-full-menu"); ?>
            <?php get_template_part("inc/mobile-full-search"); ?>

            <!-- wp:group -->
            <div class="headerMainNavLt" style="display: flex; align-items: center; gap: 45px;">

                <!-- wp:group -->
                <div class="headerMainNavMenuMob" @click="toggleMobNav">
                    <div class="navMenuMobIcon" style="display: flex">
                        <svg width="28" height="20" viewBox="0 0 28 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.5 3.33301H24.5V4.99967H3.5V3.33301ZM3.5 9.16634H24.5V10.833H3.5V9.16634ZM3.5 14.9997H24.5V16.6663H3.5V14.9997Z"
                                fill="#F9F9F9" />
                        </svg>
                    </div>
                </div>

                <div class="headerMainNavItem searchBox navItemShowOnlyMb wp-block" @click="toggleMobSearch">
                    <div class="headerMainNavItemWrap">
                        <div class="headerMainNavItemIcon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M18.0281 16.6172L22.3108 20.8999L20.8966 22.3141L16.6139 18.0314C15.074 19.2634 13.1211 20.0004 10.9971 20.0004C6.02907 20.0004 1.99707 15.9684 1.99707 11.0004C1.99707 6.0324 6.02907 2.0004 10.9971 2.0004C15.9651 2.0004 19.9971 6.0324 19.9971 11.0004C19.9971 13.1244 19.2601 15.0773 18.0281 16.6172ZM16.0218 15.8752C17.2446 14.615 17.9971 12.896 17.9971 11.0004C17.9971 7.1329 14.8646 4.0004 10.9971 4.0004C7.12957 4.0004 3.99707 7.1329 3.99707 11.0004C3.99707 14.8679 7.12957 18.0004 10.9971 18.0004C12.8927 18.0004 14.6117 17.2479 15.8719 16.0251L16.0218 15.8752Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="headerMainNavLogo" style="">
                    <a href="/" style="display: flex">
                        <img style="width: 100%;"
                            src="<?php echo get_stylesheet_directory_uri() . '/assets/images/basic_logo.svg'; ?>">
                    </a>

                </div>
                <div class="headerMainNavMenu">
                    <!-- wp:navigation -->
                </div>


                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- wp:group -->
        <div class="headerMainNavRt" style="display: flex; align-items: center; gap: 20px;">
            <div class="headerMainNavItem searchBox navItemHideMb wp-block" @click="toggleSearch">
                <div class="headerMainNavItemWrap">
                    <div class="headerMainNavItemIcon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.0281 16.6172L22.3108 20.8999L20.8966 22.3141L16.6139 18.0314C15.074 19.2634 13.1211 20.0004 10.9971 20.0004C6.02907 20.0004 1.99707 15.9684 1.99707 11.0004C1.99707 6.0324 6.02907 2.0004 10.9971 2.0004C15.9651 2.0004 19.9971 6.0324 19.9971 11.0004C19.9971 13.1244 19.2601 15.0773 18.0281 16.6172ZM16.0218 15.8752C17.2446 14.615 17.9971 12.896 17.9971 11.0004C17.9971 7.1329 14.8646 4.0004 10.9971 4.0004C7.12957 4.0004 3.99707 7.1329 3.99707 11.0004C3.99707 14.8679 7.12957 18.0004 10.9971 18.0004C12.8927 18.0004 14.6117 17.2479 15.8719 16.0251L16.0218 15.8752Z"
                                fill="white" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Окно поиска -->
            <!--            <template vibe-if="searchView">-->
            <!--                <div class="searchView" style="position:absolute;top:100%;right:0;background:white;padding:20px;">-->
            <!--                    <input type="text" placeholder="Поиск..." style="width: 100%; padding: 10px;">-->
            <!--                </div>-->
            <!--            </template>-->
            <div class="headerMainNavItem wp-block">
                <a href="/cart">
                    <div class="headerMainNavItemWrap" style="position: relative;">
                        <div class="headerMainNavItemBadge" :class="{__Show:appMainNav.navbar.cart.count}" v-show="appMainNav.navbar.cart.count">
                            {{appMainNav.navbar.cart.count}}
                        </div>
                        <div class="headerMainNavItemIcon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.00977 7.99966V5.99966C7.00977 3.23824 9.24835 0.999664 12.0098 0.999664C14.7712 0.999664 17.0098 3.23824 17.0098 5.99966V7.99966H20.0098C20.5621 7.99966 21.0098 8.44738 21.0098 8.99966V20.9997C21.0098 21.5519 20.5621 21.9997 20.0098 21.9997H4.00977C3.45749 21.9997 3.00977 21.5519 3.00977 20.9997V8.99966C3.00977 8.44738 3.45749 7.99966 4.00977 7.99966H7.00977ZM7.00977 9.99966H5.00977V19.9997H19.0098V9.99966H17.0098V11.9997H15.0098V9.99966H9.00977V11.9997H7.00977V9.99966ZM9.00977 7.99966H15.0098V5.99966C15.0098 4.34281 13.6666 2.99966 12.0098 2.99966C10.3529 2.99966 9.00977 4.34281 9.00977 5.99966V7.99966Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="headerMainNavItem wp-block">
                <a href="/whitelist">
                    <div class="headerMainNavItemWrap">
                        <div class="headerMainNavItemBadge" :class="{__Show:appMainNav.navbar.favorite.count}" v-show="appMainNav.navbar.favorite.count">
                            {{appMainNav.navbar.favorite.count}}
                        </div>
                        <div class="headerMainNavItemIcon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.9971 4.52853C14.3461 2.42 17.9761 2.49 20.2387 4.75736C22.5014 7.02472 22.5791 10.637 20.4747 12.993L11.996 21.485L3.51747 12.993C1.41314 10.637 1.4918 7.01901 3.75345 4.75736C6.01766 2.49315 9.64128 2.41687 11.9971 4.52853ZM18.8231 6.1701C17.324 4.66794 14.9037 4.60701 13.3331 6.01687L11.998 7.21524L10.6622 6.01781C9.08707 4.60597 6.67115 4.66808 5.16766 6.17157C3.67792 7.66131 3.60313 10.0473 4.97602 11.6232L11.996 18.6543L19.0162 11.6232C20.3896 10.0467 20.3151 7.66525 18.8231 6.1701Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="headerMainNavItem headerMainNavItemXl wp-block">
                <a href="/account/edit">
                    <div class="headerMainNavItemWrap">
                        <div class="headerMainNavItemIcon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.00391 21.9996C4.00391 17.5813 7.58563 13.9996 12.0039 13.9996C16.4222 13.9996 20.0039 17.5813 20.0039 21.9996H18.0039C18.0039 18.6859 15.3176 15.9996 12.0039 15.9996C8.6902 15.9996 6.00391 18.6859 6.00391 21.9996H4.00391ZM12.0039 12.9996C8.68891 12.9996 6.00391 10.3146 6.00391 6.9996C6.00391 3.6846 8.68891 0.999603 12.0039 0.999603C15.3189 0.999603 18.0039 3.6846 18.0039 6.9996C18.0039 10.3146 15.3189 12.9996 12.0039 12.9996ZM12.0039 10.9996C14.2139 10.9996 16.0039 9.2096 16.0039 6.9996C16.0039 4.7896 14.2139 2.9996 12.0039 2.9996C9.79391 2.9996 8.00391 4.7896 8.00391 6.9996C8.00391 9.2096 9.79391 10.9996 12.0039 10.9996Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            <div class="headerMainNavItem headerMainNavItemMb wp-block">
                <a href="/account">
                    <div class="headerMainNavItemWrap">
                        <div class="headerMainNavItemIcon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.00391 21.9996C4.00391 17.5813 7.58563 13.9996 12.0039 13.9996C16.4222 13.9996 20.0039 17.5813 20.0039 21.9996H18.0039C18.0039 18.6859 15.3176 15.9996 12.0039 15.9996C8.6902 15.9996 6.00391 18.6859 6.00391 21.9996H4.00391ZM12.0039 12.9996C8.68891 12.9996 6.00391 10.3146 6.00391 6.9996C6.00391 3.6846 8.68891 0.999603 12.0039 0.999603C15.3189 0.999603 18.0039 3.6846 18.0039 6.9996C18.0039 10.3146 15.3189 12.9996 12.0039 12.9996ZM12.0039 10.9996C14.2139 10.9996 16.0039 9.2096 16.0039 6.9996C16.0039 4.7896 14.2139 2.9996 12.0039 2.9996C9.79391 2.9996 8.00391 4.7896 8.00391 6.9996C8.00391 9.2096 9.79391 10.9996 12.0039 10.9996Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->