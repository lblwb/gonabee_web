<?php
/** @var array $tabs */

/** @var string|null $current */

use WCYandexTaxiDeliveryPlugin\Constants;

?>
    <span class='ygo_icon_tab'
          style="background-image: url('<?php echo WC_Yandex_Taxi_Admin_Menu::getIconFilePath(); ?>');" >&nbsp;
    </span>
    <!--<h1><?php echo Constants::getPluginName() ?></h1>-->

    <div class="ygo_menu_tabs">
        <h2 class="nav-tab-wrapper">
			<?php
			foreach ( $tabs as $page => $name ) {
				$class    = ( $page == $current ) ? 'nav-tab-active' : '';
				$dashicon = "dashicons-before ";
				switch ( $page ) {
					case YGO_PLUGIN_ID . '_settings':
						$dashicon .= "dashicons-admin-settings";
						break;
					case YGO_PLUGIN_ID . '_warehouses':
						$dashicon .= "dashicons-store";
						break;
				}
				echo "<a class='{$dashicon} nav-tab {$class}' href='" . admin_url( "admin.php?page={$page}" ) . "'>{$name}</a>";
			}
			?>
        </h2>
    </div>