<?php
// Предположим, что скидка хранится как user meta
$user_id = get_current_user_id();
$discount = get_user_meta($user_id, 'user_discount_level', true);
$discount = intval($discount); // Приведение к числу
if ($discount) {
    $discount = max(0, min($discount, 100)); // Ограничение от 0 до 100
} else {
    $discount = 3;
}


?>

<div class="accountBodyBlockHeading">
    <div class="accountBodyBlockHeadingTitle">
        Бонусная программа
    </div>
</div>
<div class="accountBodyBlockWrapper">
    <div class="pageAccountBodyBlock __Small">
        <div class="accountBodyBlockHeading">
            <div class="accountBodyBlockHeadingSubTitle">
                Уровень скидки
            </div>
            <div class="accountBodyBlockHeadingTitle">
                Скидка 0%
            </div>
        </div>
        <div class="accountBodyBlockContent">
            <div class="progressBar">
                <div class="progressBarFill" style="width: <?php echo $discount ?>%;"></div>
            </div>
        </div>
    </div>
</div>
