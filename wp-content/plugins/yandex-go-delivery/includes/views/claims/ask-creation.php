<?php

defined('ABSPATH') || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\Wrappers\OrderWrapper;
use WCYandexTaxiDeliveryPlugin\View\View;

/** @var OrderWrapper[] $allOrders */
/** @var OrderWrapper[] $activeOrders */
/** @var string $backToOrdersPageUrl */
/** @var string $createClaimUrlWithAll */
/** @var string $createClaimUrlWithNotActive */
?>

<?php if (count($allOrders) === 1): ?>
    <?php $order = array_shift($activeOrders); ?>

    <h2><?php echo __('Заказ', 'yandex-go-delivery') ?> <a href="<?php echo $order->getEditUrl() ?>" target="_blank">№<?php echo $order->getId() ?></a>
    <?php echo __('уже отправлен в ', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></h2>
<?php else: ?>
    <?php $activeOrdersHtml = implode(', ', array_map(function (OrderWrapper $order) {
        return "<a href='{$order->getEditUrl()}' target='_blank'>№{$order->getId()}</a>";
    }, $activeOrders))
    ?>

    <?php $allOrdersHtml = implode(', ', array_map(function (OrderWrapper $order) {
        return "<a href='{$order->getEditUrl()}' target='_blank'>№{$order->getId()}</a>";
    }, $allOrders))
    ?>
    <h2><?php echo __('Вы выбрали заказы', 'yandex-go-delivery') ?> <?php echo $allOrdersHtml ?> <?php echo __('для отправки в', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></h2>
    <h2><?php echo __('Заказы', 'yandex-go-delivery') ?> <?php echo $activeOrdersHtml ?> <?php echo __('уже были отправлены в', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></h2>
<?php endif ?>


<?php if (count($allOrders) !== 1): ?>
    <a class="button" href="<?php echo $createClaimUrlWithAll ?>"><?php echo __('Отправить все в', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></a>
    <a class="button" href="<?php echo $createClaimUrlWithNotActive ?>"><?php echo __('Отправить только новые в', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></a>
<?php else: ?>
    <a class="button" href="<?php echo $createClaimUrlWithAll ?>"><?php echo __('Отправить заказ в', 'yandex-go-delivery') ?> <?php echo Constants::getToPluginName() ?></a>
<?php endif ?>
<a class="button" href="<?php echo $backToOrdersPageUrl ?>"><?php echo __('Вернуться к списку заказов', 'yandex-go-delivery') ?></a>

<?php echo (new View())->buildHtml(YGO_PLUGIN_VIEWS_DIR . '/partial/_support_contact.php')?>

