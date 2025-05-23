<?php
remove_filter('the_content', 'wpautop');
function is_active($path): string
{
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $current_path === $path ? '__Active' : '';
}

?>
    <div class="myAccountNav">
        <div class="myAccountNavWrapper">
            <a href="/account/edit" class="myAccountNavBtnItem <?= is_active('/account/edit/') ?>">
                <div class="myAccountNavBtnItemWrapper">
                    <div class="myAccountNavBtnItemTitle">
                        Мои данные
                    </div>
                    <div class="myAccountNavBtnItemRhIcon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" fill="#FCFCFC" stroke="#ECECEC"/>
                            <path d="M13.3359 28.3333C13.3359 24.6514 16.3207 21.6666 20.0026 21.6666C23.6845 21.6666 26.6693 24.6514 26.6693 28.3333H25.0026C25.0026 25.5719 22.764 23.3333 20.0026 23.3333C17.2412 23.3333 15.0026 25.5719 15.0026 28.3333H13.3359ZM20.0026 20.8333C17.2401 20.8333 15.0026 18.5958 15.0026 15.8333C15.0026 13.0708 17.2401 10.8333 20.0026 10.8333C22.7651 10.8333 25.0026 13.0708 25.0026 15.8333C25.0026 18.5958 22.7651 20.8333 20.0026 20.8333ZM20.0026 19.1666C21.8443 19.1666 23.3359 17.675 23.3359 15.8333C23.3359 13.9916 21.8443 12.5 20.0026 12.5C18.1609 12.5 16.6693 13.9916 16.6693 15.8333C16.6693 17.675 18.1609 19.1666 20.0026 19.1666Z"
                                  fill="#F0C224"/>
                        </svg>
                    </div>
                </div>
            </a>
            <a href="/account/orders-list" class="myAccountNavBtnItem <?= is_active('/account/orders-list/') ?>">
                <div class="myAccountNavBtnItemWrapper">
                    <div class="myAccountNavBtnItemTitle">
                        Мои заказы
                    </div>
                    <div class="myAccountNavBtnItemRhIcon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" fill="#FCFCFC" stroke="#ECECEC"/>
                            <path d="M26.6667 28.3334H13.3333C12.8731 28.3334 12.5 27.9603 12.5 27.5V12.5C12.5 12.0398 12.8731 11.6667 13.3333 11.6667H26.6667C27.1269 11.6667 27.5 12.0398 27.5 12.5V27.5C27.5 27.9603 27.1269 28.3334 26.6667 28.3334ZM25.8333 26.6667V13.3334H14.1667V26.6667H25.8333ZM16.6667 15.8334H23.3333V17.5H16.6667V15.8334ZM16.6667 19.1667H23.3333V20.8334H16.6667V19.1667ZM16.6667 22.5H20.8333V24.1667H16.6667V22.5Z"
                                  fill="#F0C224"/>
                        </svg>
                    </div>
                </div>
            </a>
            <a href="/account/bonuses" class="myAccountNavBtnItem <?= is_active('/account/bonuses/') ?>">
                <div class="myAccountNavBtnItemWrapper">
                    <div class="myAccountNavBtnItemTitle">
                        Бонусы
                    </div>
                    <div class="myAccountNavBtnItemRhIcon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" fill="#FCFCFC" stroke="#ECECEC"/>
                            <path d="M22.5026 11.669C24.3435 11.669 25.836 13.1614 25.836 15.0023C25.836 15.6099 25.6734 16.1794 25.3895 16.6699L29.1693 16.669V18.3357H27.5026V26.669C27.5026 27.1292 27.1295 27.5023 26.6693 27.5023H13.3359C12.8757 27.5023 12.5026 27.1292 12.5026 26.669V18.3357H10.8359V16.669L14.6157 16.6699C14.3318 16.1794 14.1693 15.6099 14.1693 15.0023C14.1693 13.1614 15.6617 11.669 17.5026 11.669C18.4986 11.669 19.3926 12.1058 20.0034 12.7984C20.6126 12.1058 21.5066 11.669 22.5026 11.669ZM19.1693 18.3357H14.1693V25.8357H19.1693V18.3357ZM25.836 18.3357H20.836V25.8357H25.836V18.3357ZM17.5026 13.3357C16.5821 13.3357 15.8359 14.0819 15.8359 15.0023C15.8359 15.881 16.5158 16.6008 17.3782 16.6644L17.5026 16.669H19.1693V15.0023C19.1693 14.1676 18.5557 13.4762 17.7549 13.3546L17.627 13.3402L17.5026 13.3357ZM22.5026 13.3357C21.624 13.3357 20.9041 14.0156 20.8405 14.878L20.836 15.0023V16.669H22.5026C23.3812 16.669 24.101 15.9891 24.1647 15.1267L24.1693 15.0023C24.1693 14.0819 23.4231 13.3357 22.5026 13.3357Z"
                                  fill="#F0C224"/>
                        </svg>
                    </div>
                </div>
            </a>
            <a href="/account/help" class="myAccountNavBtnItem <?= is_active('/account/help/') ?>">
                <div class="myAccountNavBtnItemWrapper">
                    <div class="myAccountNavBtnItemTitle">
                        Помощь
                    </div>
                    <div class="myAccountNavBtnItemRhIcon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" fill="#FCFCFC" stroke="#ECECEC"/>
                            <path d="M19.9993 28.3334C15.397 28.3334 11.666 24.6024 11.666 20C11.666 15.3976 15.397 11.6667 19.9993 11.6667C24.6017 11.6667 28.3327 15.3976 28.3327 20C28.3327 24.6024 24.6017 28.3334 19.9993 28.3334ZM19.9993 26.6667C23.6813 26.6667 26.666 23.6819 26.666 20C26.666 16.3181 23.6813 13.3334 19.9993 13.3334C16.3174 13.3334 13.3327 16.3181 13.3327 20C13.3327 23.6819 16.3174 26.6667 19.9993 26.6667ZM19.166 22.5H20.8327V24.1667H19.166V22.5ZM20.8327 21.1293V21.6667H19.166V20.4167C19.166 19.9564 19.5391 19.5834 19.9993 19.5834C20.6897 19.5834 21.2493 19.0237 21.2493 18.3334C21.2493 17.643 20.6897 17.0834 19.9993 17.0834C19.3929 17.0834 18.8873 17.5152 18.7733 18.0882L17.1388 17.7612C17.4047 16.4243 18.5843 15.4167 19.9993 15.4167C21.6102 15.4167 22.916 16.7225 22.916 18.3334C22.916 19.6546 22.0374 20.7707 20.8327 21.1293Z"
                                  fill="#F0C224"/>
                        </svg>
                    </div>
                </div>
            </a>
            <a href="<?= wp_logout_url() ?>"
               class="myAccountNavBtnItem myAccountNavBtnItemMbH <?= is_active('logout/') ?>">
                <div class="myAccountNavBtnItemWrapper">
                    <div class="myAccountNavBtnItemTitle">
                        Выйти из аккаунта
                    </div>
                    <div class="myAccountNavBtnItemRhIcon">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.5" y="0.5" width="39" height="39" rx="19.5" fill="#FCFCFC" stroke="#ECECEC"/>
                            <path d="M14.1667 19.1667H20.8333V20.8334H14.1667V23.3334L10 20L14.1667 16.6667V19.1667ZM13.3327 25H15.5903C16.7655 26.0373 18.3093 26.6667 20 26.6667C23.6819 26.6667 26.6667 23.6819 26.6667 20C26.6667 16.3181 23.6819 13.3334 20 13.3334C18.3093 13.3334 16.7655 13.9627 15.5903 15H13.3327C14.8531 12.9759 17.2736 11.6667 20 11.6667C24.6023 11.6667 28.3333 15.3976 28.3333 20C28.3333 24.6024 24.6023 28.3334 20 28.3334C17.2736 28.3334 14.8531 27.0241 13.3327 25Z"
                                  fill="#F0C224"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
<?php
// Re-enable wpautop after the template
add_filter('the_content', 'wpautop');
?>