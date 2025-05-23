<?php

use WCYandexTaxiDeliveryPlugin\View\View;

defined( 'ABSPATH' ) || exit;

/** @var string $message */
?>
<h2></h2>

<?php echo ( new View() )->buildHtml( YGO_PLUGIN_VIEWS_DIR . '/partial/_error.php', [ 'error' => $message ] ) ?>


