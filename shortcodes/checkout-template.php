<?php

//echo var_dump(WC());

//if (WC()->cart->is_empty()) {
//    return '<p>Ваша корзина пуста.</p>';
//}

?>

<style>
    .cartPageHeading {
        margin-top: 68px;
        margin-bottom: 50px;
    }

    .cartPageHeadingTitle h1 sup {
        font-family: 'Manrope', sans-serif;
        font-weight: 500;
        font-size: 16px;
        line-height: 125%;
        letter-spacing: 0;
        color: #202020;
    }

    .cartPageBody {
        margin-bottom: 14vh;
    }

    .cartPageBodyWrapper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .cartPageBodyList {
        flex: auto;
        max-width: 56vw;
    }

    .cartPageBodySide {
        flex: 1;
        max-width: 26vw;
        min-width: 26vw;
    }

    .cartPageBodySideCheckoutBlock {
        border: 1px solid #E7E7E7;
        padding: 30px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .checkoutBlockHeading {
        margin-bottom: 20px;
    }

    .checkoutBlockBodyRow {
        margin-bottom: 16px;
    }

    .checkoutBlockBodyRowWrap {
        display: flex;
        justify-content: space-between;
    }

    .checkout-button {
        background: #F0C224;
        padding: 16px;
        display: block;
        border-radius: 25px;
        text-align: center;
        text-decoration: none;
        color: #252525;
        margin-bottom: 16px;
    }

    .delivery-note {
        text-align: center;
        font-weight: 500;
        font-size: 12px;
        line-height: 135%;
        letter-spacing: 0;
    }
</style>
<?php
$checkout = WC()->checkout();
?>
<div class="cartPage" id="cartPage">
    <div class="casePageMob">
        <?php
        get_template_part("template-parts/checkout/checkout", 'mb', array('checkout' => $checkout));

        ?>
    </div>
    <div class="cartPageBase" id="checkoutPc">
        <?php
        // the_content();
        // get_template_part("template-parts/checkout/checkout", 'test', array('checkout' => $checkout));
        get_template_part("template-parts/checkout/checkout", 'pc', array('checkout' => $checkout));
        ?>
    </div>
</div>