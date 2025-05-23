<?php
function nav_is_active($path): string
{
    $current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $current_path === $path ? '__Active' : '';
}

?>
<div class="mobileNav">
    <a href="/help/" class="mobileNav__item <?= nav_is_active('help/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9C16.5 13.1421 13.1421 16.5 9 16.5ZM9 15C12.3137 15 15 12.3137 15 9C15 5.68629 12.3137 3 9 3C5.68629 3 3 5.68629 3 9C3 12.3137 5.68629 15 9 15ZM8.25 11.25H9.75V12.75H8.25V11.25ZM9.75 10.0163V10.5H8.25V9.375C8.25 8.96078 8.58578 8.625 9 8.625C9.6213 8.625 10.125 8.1213 10.125 7.5C10.125 6.87868 9.6213 6.375 9 6.375C8.45423 6.375 7.9992 6.76367 7.8966 7.27933L6.42548 6.9851C6.66478 5.78189 7.7265 4.875 9 4.875C10.4497 4.875 11.625 6.05025 11.625 7.5C11.625 8.68913 10.8343 9.6936 9.75 10.0163Z"
                          fill="#F0C224"/>
                </svg>
            </div>
            <div class="mobileNav__title">Помощь</div>
        </div>
    </a>

    <a href="/reviews/" class="mobileNav__item <?= nav_is_active('reviews/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 10.5V12C6.51472 12 4.5 14.0147 4.5 16.5H3C3 13.1863 5.68629 10.5 9 10.5ZM9 9.75C6.51375 9.75 4.5 7.73625 4.5 5.25C4.5 2.76375 6.51375 0.75 9 0.75C11.4863 0.75 13.5 2.76375 13.5 5.25C13.5 7.73625 11.4863 9.75 9 9.75ZM9 8.25C10.6575 8.25 12 6.9075 12 5.25C12 3.5925 10.6575 2.25 9 2.25C7.3425 2.25 6 3.5925 6 5.25C6 6.9075 7.3425 8.25 9 8.25ZM13.5 16.125L11.2958 17.2838L11.7168 14.8294L9.93353 13.0912L12.3979 12.7331L13.5 10.5L14.6021 12.7331L17.0665 13.0912L15.2832 14.8294L15.7042 17.2838L13.5 16.125Z"
                          fill="#F0C224"/>
                </svg>
            </div>
            <div class="mobileNav__title">Отзывы</div>
        </div>
    </a>

    <a href="/about/" class="mobileNav__item <?= nav_is_active('about/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.375 1.5C14.6532 1.5 16.5 3.34682 16.5 5.625V7.5C16.5 8.16608 16.2106 8.7645 15.7507 9.17648L15.75 12.75C15.75 13.7294 15.1242 14.5627 14.2507 14.8718L14.25 15.75C14.25 16.1642 13.9142 16.5 13.5 16.5H4.5C4.08579 16.5 3.75 16.1642 3.75 15.75L3.75007 14.872C2.87614 14.5631 2.25 13.7297 2.25 12.75V4.5C2.25 2.84314 3.59314 1.5 5.25 1.5H12.375ZM7.125 8.25H3.75V12.75C3.75 13.1346 4.03953 13.4516 4.41253 13.495L4.5 13.5H13.5C13.8846 13.5 14.2016 13.2105 14.245 12.8375L14.25 12.75V9.75L9.7233 9.75068C9.54105 11.0225 8.44717 12 7.125 12H4.5V10.5H7.125C7.70977 10.5 8.1903 10.0538 8.24483 9.48337L8.25 9.375C8.25 8.79023 7.80382 8.3097 7.23334 8.25517L7.125 8.25ZM12.375 3H5.25C4.45923 3 3.81137 3.61191 3.75412 4.38805L3.75 4.5V6.75H7.125C8.17215 6.75 9.07605 7.36313 9.4974 8.25H14.25C14.6346 8.25 14.9516 7.9605 14.995 7.58745L15 7.5V5.625C15 4.22358 13.9018 3.07865 12.519 3.00388L12.375 3Z"
                          fill="#F0C224"/>
                </svg>
            </div>
            <div class="mobileNav__title">О нас</div>
        </div>
    </a>

    <a href="/contacts/" class="mobileNav__item <?= nav_is_active('contacts/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 17.7959L4.22703 13.023C1.59099 10.3869 1.59099 6.11307 4.22703 3.47703C6.86307 0.84099 11.1369 0.84099 13.773 3.47703C16.409 6.11307 16.409 10.3869 13.773 13.023L9 17.7959ZM12.7123 11.9623C14.7625 9.91208 14.7625 6.58794 12.7123 4.53769C10.6621 2.48744 7.33794 2.48744 5.28769 4.53769C3.23744 6.58794 3.23744 9.91208 5.28769 11.9623L9 15.6746L12.7123 11.9623ZM9 9.75C8.17155 9.75 7.5 9.07845 7.5 8.25C7.5 7.42157 8.17155 6.75 9 6.75C9.82845 6.75 10.5 7.42157 10.5 8.25C10.5 9.07845 9.82845 9.75 9 9.75Z"
                          fill="#F0C224"/>
                </svg>
            </div>
            <div class="mobileNav__title">Контакты</div>
        </div>
    </a>

    <a href="<?= wp_logout_url() ?>" class="mobileNav__item mobileNav__item--disabled">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__icon">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.5">
                        <path d="M3.75 8.25H9.75V9.75H3.75V12L0 9L3.75 6V8.25ZM2.99945 13.5H5.03126C6.08897 14.4335 7.47833 15 9 15C12.3137 15 15 12.3137 15 9C15 5.68629 12.3137 3 9 3C7.47833 3 6.08897 3.56645 5.03126 4.5H2.99945C4.36776 2.67833 6.54627 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C6.54627 16.5 4.36776 15.3217 2.99945 13.5Z"
                              fill="#1F1F1F"/>
                    </g>
                </svg>
            </div>
            <div class="mobileNav__title">Выйти из аккаунта</div>
        </div>
    </a>

    <a href="/privacy-policy/" class="mobileNav__item <?= nav_is_active('privacy-policy/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__title">Политика конфиденциальности</div>
        </div>
    </a>

    <a href="/public-offer/" class="mobileNav__item <?= nav_is_active('public-offer/') ?>">
        <div class="mobileNav__wrapper">
            <div class="mobileNav__title">Договор оферты</div>
        </div>
    </a>
</div>