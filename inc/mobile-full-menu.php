<!-- mb -->
<div v-show="appMainNav.mob.nav_menu.show" style="position: absolute;bottom: 0;">
    <div class="mobileMenuOverlay"
         style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:99;">
        <div class="mobileMenuContent"
             style="background:#222222;width:100%;height:100%;position:absolute;left:0;top:0;padding:7vh 14px;">

            <div class="mobileMenuContentHeader" style="margin-bottom: 24px;padding-bottom: 24px;border-bottom: solid 1px #ffffff50;">
                <div class="mobileMenuContentHeaderWrapper"
                     style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="mobileMenuContentHeaderRow">
                        <div class="mobileMenuContentHeaderLt">
                            <div class="mobileMenuContentHeaderLtWrapper"
                                 style="display: flex; gap: 28px; align-items: center;">
                                <!-- Здесь меню -->
                                <div @click="toggleMobNav"
                                     style="cursor:pointer;">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.82223 10L2.32812 3.5059L3.50663 2.32739L10.0007 8.82143L16.4948 2.32739L17.6733 3.5059L11.1792 10L17.6733 16.494L16.4948 17.6726L10.0007 11.1785L3.50663 17.6726L2.32812 16.494L8.82223 10Z"
                                            fill="#F9F9F9"/>
                                    </svg>
                                </div>
                                <div class="mobileMenuContentSearch">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.0232 13.8477L18.5921 17.4166L17.4136 18.5951L13.8447 15.0262C12.5615 16.0528 10.9341 16.667 9.16406 16.667C5.02406 16.667 1.66406 13.307 1.66406 9.16699C1.66406 5.02699 5.02406 1.66699 9.16406 1.66699C13.3041 1.66699 16.6641 5.02699 16.6641 9.16699C16.6641 10.937 16.0499 12.5644 15.0232 13.8477ZM13.3513 13.2293C14.3703 12.1792 14.9974 10.7467 14.9974 9.16699C14.9974 5.94408 12.387 3.33366 9.16406 3.33366C5.94115 3.33366 3.33073 5.94408 3.33073 9.16699C3.33073 12.3899 5.94115 15.0003 9.16406 15.0003C10.7437 15.0003 12.1762 14.3732 13.2264 13.3542L13.3513 13.2293Z"
                                            fill="white"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="mobileMenuContentHeaderRow">
                        <!-- wp:group -->
                        <div class="headerMainNavRt"
                             style="display: flex; align-items: center; gap: 20px;">

                            <!-- Окно поиска -->
                            <template x-if="searchView">
                                <div class="searchView"
                                     style="position:absolute;top:100%;right:0;background:white;padding:20px;">
                                    <input type="text" placeholder="Поиск..."
                                           style="width: 100%; padding: 10px;">
                                </div>
                            </template>

                            <div class="headerMainNavItem wp-block">
                                <a href="/cart">
                                    <div class="headerMainNavItemWrap" style="position: relative;">
                                        <div class="headerMainNavItemBadge" v-if="appMainNav.navbar.cart.count">
                                            {{appMainNav.navbar.cart.count}}
                                        </div>
                                        <div class="headerMainNavItemIcon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                        d="M7.00977 7.99966V5.99966C7.00977 3.23824 9.24835 0.999664 12.0098 0.999664C14.7712 0.999664 17.0098 3.23824 17.0098 5.99966V7.99966H20.0098C20.5621 7.99966 21.0098 8.44738 21.0098 8.99966V20.9997C21.0098 21.5519 20.5621 21.9997 20.0098 21.9997H4.00977C3.45749 21.9997 3.00977 21.5519 3.00977 20.9997V8.99966C3.00977 8.44738 3.45749 7.99966 4.00977 7.99966H7.00977ZM7.00977 9.99966H5.00977V19.9997H19.0098V9.99966H17.0098V11.9997H15.0098V9.99966H9.00977V11.9997H7.00977V9.99966ZM9.00977 7.99966H15.0098V5.99966C15.0098 4.34281 13.6666 2.99966 12.0098 2.99966C10.3529 2.99966 9.00977 4.34281 9.00977 5.99966V7.99966Z"
                                                        fill="white"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="headerMainNavItem wp-block">
                                <a href="/whitelist">
                                    <div class="headerMainNavItemWrap">
                                        <div class="headerMainNavItemBadge" v-if="appMainNav.navbar.favorite.count">
                                            {{appMainNav.navbar.favorite.count}}
                                        </div>
                                        <div class="headerMainNavItemIcon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                        d="M11.9971 4.52853C14.3461 2.42 17.9761 2.49 20.2387 4.75736C22.5014 7.02472 22.5791 10.637 20.4747 12.993L11.996 21.485L3.51747 12.993C1.41314 10.637 1.4918 7.01901 3.75345 4.75736C6.01766 2.49315 9.64128 2.41687 11.9971 4.52853ZM18.8231 6.1701C17.324 4.66794 14.9037 4.60701 13.3331 6.01687L11.998 7.21524L10.6622 6.01781C9.08707 4.60597 6.67115 4.66808 5.16766 6.17157C3.67792 7.66131 3.60313 10.0473 4.97602 11.6232L11.996 18.6543L19.0162 11.6232C20.3896 10.0467 20.3151 7.66525 18.8231 6.1701Z"
                                                        fill="white"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="headerMainNavItem wp-block">
                                <a href="/account">
                                    <div class="headerMainNavItemWrap">
                                        <div class="headerMainNavItemIcon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4.00391 21.9996C4.00391 17.5813 7.58563 13.9996 12.0039 13.9996C16.4222 13.9996 20.0039 17.5813 20.0039 21.9996H18.0039C18.0039 18.6859 15.3176 15.9996 12.0039 15.9996C8.6902 15.9996 6.00391 18.6859 6.00391 21.9996H4.00391ZM12.0039 12.9996C8.68891 12.9996 6.00391 10.3146 6.00391 6.9996C6.00391 3.6846 8.68891 0.999603 12.0039 0.999603C15.3189 0.999603 18.0039 3.6846 18.0039 6.9996C18.0039 10.3146 15.3189 12.9996 12.0039 12.9996ZM12.0039 10.9996C14.2139 10.9996 16.0039 9.2096 16.0039 6.9996C16.0039 4.7896 14.2139 2.9996 12.0039 2.9996C9.79391 2.9996 8.00391 4.7896 8.00391 6.9996C8.00391 9.2096 9.79391 10.9996 12.0039 10.9996Z"
                                                    fill="white"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- /wp:group -->
                    </div>
                </div>
            </div>

            <div class="mobileMenuContentWrapper" style="">


                <div class="mobileMenuContentHeaderSubHeader"
                     style="">
                    <div class="headerSubHeaderTitle"
                         style="">
                        Меню
                    </div>
                </div>

                <div class="mobileMenuContentMenu">
                    <?php if (function_exists('quadmenu')): ?>
                        <?php quadmenu(array('menu' => 'MainHead', 'theme' => 'default_theme')); ?>
                    <?php endif; ?>


                    <nav id="quadmenu"
                         class="quadmenu-default_theme quadmenu-v3.2.1 quadmenu-align-right quadmenu-divider-show quadmenu-carets-show quadmenu-background-color quadmenu-mobile-shadow-show quadmenu-dropdown-shadow-hide quadmenu-is-embed quadmenu-touch js"
                         data-template="embed" data-theme="default_theme" data-unwrap="0"
                         data-breakpoint="768">
                        <div class="quadmenu-container">
                            <div id="quadmenu_0" class="quadmenu-navbar-collapse collapsed in">
                                <ul class="quadmenu-navbar-nav">
                                    <li id="menu-item-103"
                                        class="quadmenu-item-103 quadmenu-item quadmenu-item-object-custom quadmenu-item-type-default quadmenu-item-level-0 quadmenu-has-title quadmenu-has-link quadmenu-has-background quadmenu-dropdown-right">
                                        <a href="/sale">
                                                        <span class="quadmenu-item-content"
                                                              style="display: flex; align-items: center; gap: 12px">
                                                            <span class="icon"  style="display: flex; align-items: center;">
                                                                <svg width="30" height="30" viewBox="0 0 30 30"
                                                                     fill="none" xmlns="http://www.w3.org/2000/svg"><path
                                                                        d="M15 28.75C9.82233 28.75 5.625 24.5526 5.625 19.375C5.625 16.6827 6.75983 14.2556 8.57724 12.5457C10.255 10.9672 14.375 8.12439 13.75 1.875C21.25 6.875 25 11.875 17.5 19.375C18.75 19.375 20.625 19.375 23.75 16.287C24.0871 17.254 24.375 18.2931 24.375 19.375C24.375 24.5526 20.1776 28.75 15 28.75Z"
                                                                        fill="#CE1B19"/></svg>
                                                            </span>
                                                            <span
                                                                class="quadmenu-text hover t_1000">Распродажа</span></span></a>
                                    </li>
                                    <li id="menu-item-104"
                                        class="quadmenu-item-104 quadmenu-item quadmenu-item-object-custom quadmenu-item-type-default quadmenu-item-level-0 quadmenu-has-title quadmenu-has-link quadmenu-has-background quadmenu-dropdown-right">
                                        <a href="#">
                                                        <span class="quadmenu-item-content"
                                                              style="display: flex; align-items: center; gap: 12px">
                                                            <span class="icon"  style="display: flex; align-items: center;">
                                                                <svg width="30" height="30" viewBox="0 0 30 30"
                                                                     fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.1299 2.50354C20.5462 2.50354 22.5049 4.46229 22.5049 6.87854C22.5049 7.55004 22.3537 8.18621 22.0833 8.75484L26.2549 8.75354C26.9453 8.75354 27.5049 9.31318 27.5049 10.0035V15.0035C27.5049 15.6939 26.9453 16.2535 26.2549 16.2535H25.0049V26.2535C25.0049 26.9439 24.4453 27.5035 23.7549 27.5035H6.25488C5.56453 27.5035 5.00488 26.9439 5.00488 26.2535V16.2535H3.75488C3.06453 16.2535 2.50488 15.6939 2.50488 15.0035V10.0035C2.50488 9.31318 3.06453 8.75354 3.75488 8.75354L7.92652 8.75484C7.65617 8.18621 7.50488 7.55004 7.50488 6.87854C7.50488 4.46229 9.46365 2.50354 11.8799 2.50354C13.1049 2.50354 14.2123 3.00699 15.0064 3.81826C15.7975 3.00699 16.9049 2.50354 18.1299 2.50354ZM22.5049 16.2535H7.50488V25.0035H22.5049V16.2535ZM25.0049 11.2535H5.00488V13.7535H25.0049V11.2535ZM11.8799 5.00354C10.8444 5.00354 10.0049 5.843 10.0049 6.87854C10.0049 7.85315 10.7485 8.6541 11.6993 8.74495L11.8799 8.75354H13.7549V6.87854C13.7549 5.90392 13.0113 5.10298 12.0605 5.01211L11.8799 5.00354ZM18.1299 5.00354L17.9493 5.01211C17.0579 5.0973 16.3487 5.80658 16.2634 6.69796L16.2549 6.87854V8.75354H18.1299L18.3104 8.74495C19.2613 8.6541 20.0049 7.85315 20.0049 6.87854C20.0049 5.90392 19.2613 5.10298 18.3104 5.01211L18.1299 5.00354Z" fill="#F0C224"/></svg>
                                                            </span>
                                                            <span
                                                                class="quadmenu-text hover t_1000">Подарочные сертификаты</span></span></a>
                                    </li>
                                    <li id="menu-item-104"
                                        class="quadmenu-item-104 quadmenu-item quadmenu-item-object-custom quadmenu-item-type-default quadmenu-item-level-0 quadmenu-has-title quadmenu-has-link quadmenu-has-background quadmenu-dropdown-right">
                                        <a href="/account/helper">
                                                        <span class="quadmenu-item-content"
                                                              style="display: flex; align-items: center; gap: 12px">
                                                            <span class="icon" style="display: flex; align-items: center;">
                                                              <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.0049 27.5C8.10132 27.5 2.50488 21.9035 2.50488 15C2.50488 8.09644 8.10132 2.5 15.0049 2.5C21.9084 2.5 27.5049 8.09644 27.5049 15C27.5049 21.9035 21.9084 27.5 15.0049 27.5ZM15.0049 25C20.5278 25 25.0049 20.5229 25.0049 15C25.0049 9.47715 20.5278 5 15.0049 5C9.48203 5 5.00488 9.47715 5.00488 15C5.00488 20.5229 9.48203 25 15.0049 25ZM13.7549 18.75H16.2549V21.25H13.7549V18.75ZM16.2549 16.6939V17.5H13.7549V15.625C13.7549 14.9346 14.3145 14.375 15.0049 14.375C16.0404 14.375 16.8799 13.5355 16.8799 12.5C16.8799 11.4645 16.0404 10.625 15.0049 10.625C14.0953 10.625 13.3369 11.2728 13.1659 12.1322L10.714 11.6418C11.1128 9.63649 12.8824 8.125 15.0049 8.125C17.4211 8.125 19.3799 10.0838 19.3799 12.5C19.3799 14.4819 18.062 16.156 16.2549 16.6939Z" fill="#F0C224"/></svg>
                                                            </span>
                                                            <span
                                                                class="quadmenu-text hover t_1000">Центр помощи</span></span></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>

                <div class="mobileMenuContentHeaderSubHeader">
                    <div class="headerSubHeaderTitle">
                        Новый сезон
                    </div>
                </div>

                <div class="mobileMenuContentBanner"
                     style="background: url('<?php echo get_stylesheet_directory_uri() . '/assets/images/nav_main_banner.png'; ?>');">
                    <div class="mobileMenuContentBannerWrapper">
                        <div class="mobileMenuContentBannerTop"
                             style="">
                            <div class="mobileMenuContentBannerTopHeading"
                                 style="">
                                Весенние
                                комплекты
                            </div>
                        </div>
                        <div class="mobileMenuContentBannerFooter">
                            <button class="bannerFooterBtn">
                                <div class="bannerFooterBtnHeading">
                                    <div class="bannerFooterBtnHeadingTitle">
                                        К покупкам
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>


                <div class="mobileMenuContentFooter">
                    <div class="mobileMenuContentFooterLine" style="">
                        <hr>
                    </div>
                    <div class="mobileMenuContentFooterNav">
                        <div class="footerNavWrapper"
                             style="">
                            <div class="footerNavBlock" style="">
                                <div class="footerNavBlockHeading" style="">
                                    <div class="footerNavBlockHeadingTitle" style="">
                                        О нас
                                    </div>
                                </div>
                                <div class="footerNavBlockBody">
                                    <div class="footerNavBlockBodyList"
                                         style="">
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">Миссия бренда</a>
                                        </div>
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">Философия бренда</a>
                                        </div>
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">Галерея</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footerNavBlock" style="flex: 1">
                                <div class="footerNavBlockHeading" style="margin-bottom: 20px">
                                    <div class="footerNavBlockHeadingTitle" style="color: #F9F9F950">
                                        Контакты
                                    </div>
                                </div>
                                <div class="footerNavBlockBody">
                                    <div class="footerNavBlockBodyList"
                                         style="display: flex; flex-flow: column; gap: 12px">
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">Свяжитесь с нами</a>
                                        </div>
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">8 (800) 300 49 90</a>
                                        </div>
                                        <div class="footerNavBlockBodyListItem">
                                            <a href="#">order@ktsportwear.com</a>
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
</div>