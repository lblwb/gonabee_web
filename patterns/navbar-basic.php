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
    <div class="headerMainNavWrapper">
        <div class="headerMainNav wp-block" id="headerMainNav" v-cloak
            data-nonce="<?php echo esc_attr(wp_create_nonce('toggle_favorite_nonce')); ?>"
            data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
            data-count="<?php $wc_cart = WC()->cart;
                        echo count($wc_cart->get_cart()) ?>"
            data-user-auth="<?php echo is_user_logged_in(); ?>">
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
                            <svg width="28" height="20" viewBox="0 0 28 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
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
                <div class="headerMainNavItem searchBox navItemHideMb wp-block" @click="toggleSearchFull">
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
                <!-- Окно поиска -->
                <!--            <template vibe-if="searchView">-->
                <!--                <div class="searchView" style="position:absolute;top:100%;right:0;background:white;padding:20px;">-->
                <!--                    <input type="text" placeholder="Поиск..." style="width: 100%; padding: 10px;">-->
                <!--                </div>-->
                <!--            </template>-->
                <div class="headerMainNavItem wp-block">
                    <a href="/cart">
                        <div class="headerMainNavItemWrap" style="position: relative;">
                            <div class="headerMainNavItemBadge" :class="{__Show:appMainNav.navbar.cart.count}"
                                v-show="appMainNav.navbar.cart.count">
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
                            <div class="headerMainNavItemBadge" :class="{__Show:appMainNav.navbar.favorite.count}"
                                v-show="appMainNav.navbar.favorite.count">
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
                    <a href="/account/edit" v-if="appMainNav.navbar.user.isAuth">
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
                    <a @click="viewModalAccount('getExistUser')" v-if="!appMainNav.navbar.user.isAuth">
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
                    <a href="/account/" v-if="appMainNav.navbar.user.isAuth">
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
                    <a @click="viewModalAccount('getExistUser')" v-if="!appMainNav.navbar.user.isAuth">
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

            <div class="modalFullSearch" v-if="appMainNav.navbar.nav_search.show" style="">
                <div class="modalFullSearchWrapper">
                    <div class="modalFullSearchTop">
                        <div class="modalFullSearchTopInputBox" style="">
                            <div class="modalFullSearchTopInputBoxWrapper gridWrap" style="">
                                <div class="modalFullSearchTopInputBoxIcon">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.0271 14.6173L20.3098 18.9L18.8956 20.3142L14.6129 16.0315C13.073 17.2635 11.1201 18.0005 8.99609 18.0005C4.02809 18.0005 -0.00390625 13.9685 -0.00390625 9.00049C-0.00390625 4.03249 4.02809 0.000488281 8.99609 0.000488281C13.9641 0.000488281 17.9961 4.03249 17.9961 9.00049C17.9961 11.1245 17.2591 13.0774 16.0271 14.6173ZM14.0208 13.8753C15.2436 12.6151 15.9961 10.8961 15.9961 9.00049C15.9961 5.13299 12.8636 2.00049 8.99609 2.00049C5.12859 2.00049 1.99609 5.13299 1.99609 9.00049C1.99609 12.868 5.12859 16.0005 8.99609 16.0005C10.8917 16.0005 12.6107 15.248 13.8709 14.0252L14.0208 13.8753Z"
                                            fill="white" />
                                    </svg>
                                </div>
                                <div class="modalFullSearchTopInput" style="width: 100%;">
                                    <input style="" v-model="appMainNav.mob.nav_search.context.queryText" @input="onInputSearchHandler"
                                        placeholder="Начните поиск..." type="text">
                                </div>
                            </div>
                        </div>
                        <div class="mobileSearchContentHeaderExit" @click="toggleSearchFull" style="position:absolute; top: 35px; right: 35px">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                    fill="#F9F9F9" />
                            </svg>
                        </div>
                    </div>
                    <div class="modalFullSearchBody" style="padding-top: 30px;">
                        <div class="mobileSearchContentSearchCat gridWrap">
                            <div class="searchCatList" v-if="appMainNav.mob.nav_search.cat.result.list" style="flex-flow: row  wrap; gap: 30px;">
                                <div class="searchCatListItem" v-for="catItem in appMainNav.mob.nav_search.cat.result.list"
                                    :key="catItem.name">
                                    <a class="searchCatListItemCard" :href="catItem.link">
                                        <div class="searchCatListItemCardWrapper" style="">
                                            <div class="searchCatListItemCardImg" v-if="catItem.image">
                                                <img :src="catItem.image" :alt="catItem.name">
                                            </div>
                                            <div class="searchCatListItemCardImg __Bord" v-else>
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAALQAAAD6CAYAAAAIn20uAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMXSURBVHgB7dfBSsNAAEXR0YpCoSC4ciH4/1/lThC7anEhCM5o0W/I5Rx4Icn6EiZX499h7nFuP7cbsA2nueNlf+GukJ/n7uauB2zHavb+cn9eQT/MPQ3YtnXC+Al6xXw3YPtu1/HiMKBh77xMyU7QpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCmCJkXQpAiaFEGTImhSBE2KoEkRNCkr6K8BESvojwEN5xX064CG9928fM5dzR0GbNf6ML/tLg+n8Rv2zdztgG1Y/3/ryPwyd1wvvgEmbxUF78bXTgAAAABJRU5ErkJggg=="
                                                    :alt="catItem.name"
                                                    style="object-fit: cover;object-position: bottom; min-height: 16vh;max-width: 21.5vw;">
                                            </div>
                                            <div class="searchCatListItemCardHeading">
                                                <div class="cardHeadingTitle"
                                                    style="">
                                                    {{catItem.name}}
                                                </div>
                                                <div class="cardHeadingSubTitle" v-if="catItem.parent_cat"
                                                    style="">
                                                    {{catItem.parent_cat.name}}
                                                </div>
                                                <div class="cardHeadingSubTitle" v-else>
                                                    ——
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="searchCatList" v-else>—</div>
                        </div>
                        <div class="mobileMenuContentSearchSep" style="" v-if="appMainNav.mob.nav_search.cat.result.list && appMainNav.mob.nav_search.cat.result.list.length">
                            <hr>
                        </div>
                        <div class="mobileMenuContentSearchResult gridWrap">
                            <div class="searchResultHeading" style="">
                                <div class="searchResultHeadingTitle" style="">
                                    Результаты поиска ({{appMainNav.mob.nav_search.products.count}})
                                </div>
                            </div>
                            <div class="searchResultList" v-if="appMainNav.mob.nav_search.products.result.list" v-attach-events>
                                <div class="searchResultListWrapper" style="">
                                    <div class="searchResultListItem previewSliderItem"
                                        v-for="productItem in appMainNav.mob.nav_search.products.result.list"
                                        :key="productItem.name">
                                        <div class="previewSliderItemBlock" style="position: relative;">
                                            <div class="itemBlockHeading">
                                                <div class="itemBlockHeadingWrapper"
                                                    style="display: flex; justify-content: space-between;">
                                                    <div class="previewSliderItemWhiteList"
                                                        :class="{__Active: getSelectedWhtLst(productItem.ID)}"
                                                        data-product-id="productItem.ID">
                                                        <div class="whiteListBtn" style="background: #FFFFFF;"
                                                            @click="addToWhtListMob({imageUrl: productItem.image, productId: productItem.ID})">
                                                            <div class="whiteListBtnIcon"
                                                                v-if="!getSelectedWhtLst(productItem.data.ID)">
                                                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <rect width="30" height="30" rx="15" fill="white" />
                                                                    <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019ZM19.55 11.1134C18.5506 10.112 16.9371 10.0713 15.89 11.0112L15 11.8102L14.1094 11.0119C13.0594 10.0706 11.4487 10.1121 10.4464 11.1144C9.45325 12.1075 9.40339 13.6982 10.3187 14.7488L14.9986 19.4362L19.6788 14.7488C20.5944 13.6978 20.5447 12.1102 19.55 11.1134Z"
                                                                        fill="#1F1F1F" />
                                                                </svg>
                                                            </div>
                                                            <div class="whiteListBtnIcon" v-else>
                                                                <svg width="30" height="30" viewBox="0 0 30 30" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <rect width="30" height="30" rx="15" fill="white" />
                                                                    <path d="M14.9994 10.019C16.5654 8.61333 18.9854 8.66 20.4938 10.1716C22.0022 11.6831 22.054 14.0913 20.6511 15.662L14.9986 21.3233L9.34628 15.662C7.9434 14.0913 7.99584 11.6793 9.5036 10.1716C11.0131 8.6621 13.4288 8.61125 14.9994 10.019Z"
                                                                        fill="#CE1B19" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="previewSliderItemImage">
                                                <div class="previewSliderItemImageWrapper">
                                                    <div class="previewSliderItemImageSubGallery swiper">
                                                        <div class="itemImageSubGalleryWrap swiper-wrapper">
                                                            <div class="itemImageSubGalleryItem swiper-slide"
                                                                v-for="galleryItem in productItem.gallery"
                                                                :data-color-id="productItem.colors.id"
                                                                :data-href="productItem.id"
                                                                @click="showProductNav"
                                                                :style="`background-image: url(${productItem.image}); text-decoration: none;`">
                                                            </div>
                                                        </div>
                                                        <!--pag-->
                                                        <div class="bottomPaginate"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="previewSliderItemBlockHeading">
                                                <div class="itemBlockHeadingTitle">
                                                    <a :href="productItem.link"
                                                        style="">{{productItem.name}}</a>
                                                </div>
                                                <div class="itemBlockHeadingPrice" v-html="productItem.is_stock ? productItem.price : 'Товара нет в наличии'"></div>
                                                <!--     Colors            -->
                                                <div class="colorSelectorWrapper" style="display: flex;justify-content: space-between; align-items: center;">
                                                    <div class="colorScrollBtn __left" aria-label="Scroll left"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true">
                                                            <path d="M11 15 4.54 8.53a.74.74 0 0 1 0-1.06L11 1l.35.35a1 1 0 0 1 0 1.41L6.13 8l5.26 5.24a1 1 0 0 1 0 1.41z" fill="currentColor" fill-rule="evenodd"></path>
                                                        </svg></div>

                                                    <div class="itemBlockHeadingSelColor">
                                                        <div class="colorBox" v-for="color in productItem.colors_list"
                                                            :key="color.slug">

                                                            <div class="color-circle"
                                                                :title="color.name"
                                                                @click="selectCartColorProduct(productItem.link, color.slug)"
                                                                :style="{ backgroundColor: color.code }"
                                                                :data-color-id="color.slug">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="colorScrollBtn __right" aria-label="Scroll right"><svg height="16" width="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" data-lll-pl="icon" aria-hidden="true">
                                                            <path d="m5 15 6.5-6.47a.74.74 0 0 0 0-1.06L5 1l-.35.35a1 1 0 0 0 0 1.41L9.87 8l-5.26 5.24a1 1 0 0 0 0 1.41z" fill="currentColor" fill-rule="evenodd"></path>
                                                        </svg></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modalAuth" v-if="appMainNav.navbar.modals.account.step !== ''">
                <div class="modalAuthWrapper">
                    <div class="modalAuthBlock" style=""
                        v-if="appMainNav.navbar.modals.account.step === 'getExistUser'">
                        <div class="modalAuthBlockExit" @click="viewModalAccount('')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"
                                    fill="#222222" />
                            </svg>
                        </div>
                        <div class="modalAuthBlockHeading">
                            <div class="modalAuthBlockHeadingTitle">
                                Войти или создать аккаунт
                            </div>
                            <div class="modalAuthBlockHeadingDesc">
                                Войдите или создайте аккаунт, чтобы иметь возможность<br>
                                добавлять товары в избранное, применять промокоды, а так<br>
                                же пользоваться личным кабинетом
                            </div>
                        </div>
                        <div class="modalAuthBlockBody">
                            <div class="modalAuthBlockBodyLabel">
                                Email адрес
                            </div>
                            <div class="modalAuthBlockBodyInput">
                                <input type="email" v-model="appMainNav.navbar.modals.account.field.email"
                                    placeholder="Ivanov@gmail.com"
                                    style="padding: 16px 20px; width: 100%; border: solid 1px #ECECEC; background: transparent; border-radius: 100px;">
                            </div>
                        </div>
                        <div class="modalAuthBlockFooter" @click="checkEmailExists">
                            <div class="modalAuthBlockFooterBtn">
                                <div class="footerBtnHeading">
                                    <div class="footerBtnHeadingTitle">Продолжить</div>
                                </div>
                            </div>
                            <div class="modalAuthBlockFooterDesc">
                                Нажимая на кнопку “продолжить”<br />
                                вы соглашаетесь с политикой конфиденциальности
                            </div>
                        </div>
                    </div>
                    <div class="modalAuthBlock" v-if="appMainNav.navbar.modals.account.step === 'authLogin'">
                        <div class="modalAuthBlockExit" @click="viewModalAccount('')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"
                                    fill="#222222" />
                            </svg>
                        </div>
                        <div class="modalAuthBlockHeading">
                            <div class="modalAuthBlockHeadingTitle">
                                Войти в аккаунт
                            </div>
                            <div class="modalAuthBlockHeadingDesc">
                                Авторизоваться как: {{appMainNav.navbar.modals.account.field.email}}
                            </div>
                        </div>
                        <div class="modalAuthBlockBody">
                            <div class="modalAuthBlockBodyLabel">
                                Пароль
                            </div>
                            <div class="modalAuthBlockBodyInput">
                                <input type="password" v-model="appMainNav.navbar.modals.account.field.password"
                                    placeholder="********" style="">
                            </div>
                            <div class="modalAuthBlockBodyDesc">
                                Ваш пароль должен быть не менее 8 символов
                            </div>
                        </div>
                        <div class="modalAuthBlockFooter">
                            <div class="modalAuthBlockFooterBtn" @click="doLogin">
                                <div class="footerBtnHeading">
                                    <div class="footerBtnHeadingTitle">Войти</div>
                                </div>
                            </div>
                            <div class="modalAuthBlockFooterDesc">
                                Нажимая на кнопку “продолжить”<br />
                                вы соглашаетесь с политикой конфиденциальности
                            </div>
                        </div>
                    </div>
                    <div class="modalAuthBlock" v-if="appMainNav.navbar.modals.account.step === 'authCreateNew'">
                        <div class="modalAuthBlockExit" @click="viewModalAccount('')">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.5859 12L2.79297 4.20706L4.20718 2.79285L12.0001 10.5857L19.793 2.79285L21.2072 4.20706L13.4143 12L21.2072 19.7928L19.793 21.2071L12.0001 13.4142L4.20718 21.2071L2.79297 19.7928L10.5859 12Z"
                                    fill="#222222" />
                            </svg>
                        </div>
                        <div class="modalAuthBlockHeading">
                            <div class="modalAuthBlockHeadingTitle">
                                Создать аккаунт
                            </div>
                            <div class="modalAuthBlockHeadingDesc">
                                Зарегистрироваться как: {{appMainNav.navbar.modals.account.field.email}}
                            </div>
                        </div>
                        <div class="modalAuthBlockBody">
                            <div class="modalAuthBlockBodyLabel">
                                Пароль
                            </div>
                            <div class="modalAuthBlockBodyInput">
                                <input type="password" v-model="appMainNav.navbar.modals.account.field.password"
                                    placeholder="********" style="">
                            </div>
                            <div class="modalAuthBlockBodyCheckbox"
                                style="display: flex;gap: 10px;align-items: center; margin-top: 16px">
                                <div class="modalAuthBlockBodyCheckboxInput" style=""
                                    v-if="appMainNav.navbar.modals.account.field.email_checkbox">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" rx="4" fill="#F0C224" />
                                        <path d="M8.66451 12.1139L14.7928 5.98566L15.7356 6.92846L8.66451 13.9995L4.42188 9.75691L5.36469 8.81411L8.66451 12.1139Z"
                                            fill="white" />
                                    </svg>
                                </div>
                                <div class="modalAuthBlockBodyCheckboxLabel"
                                    style="font-family: 'Montserrat',sans-serif;font-style: normal;font-weight: 500;font-size: 14px;line-height: 145%;color: #1F1F1F;">
                                    Я согласен получать скидки <br />и специальные предложения по email рассылке
                                </div>
                            </div>
                        </div>
                        <div class="modalAuthBlockFooter">
                            <div class="modalAuthBlockFooterBtn" @click="doRegister">
                                <div class="footerBtnHeading">
                                    <div class="footerBtnHeadingTitle">Создать аккаунт</div>
                                </div>
                            </div>
                            <div class="modalAuthBlockFooterDesc">
                                Нажимая на кнопку “продолжить”<br />
                                вы соглашаетесь с политикой конфиденциальности
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->