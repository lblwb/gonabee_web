<?php

if ( class_exists( 'QuadLayers\\WP_Notice_Plugin_Promote\\Load' ) ) {
	/**
	 *  Promote constants
	 */
	define( 'QUADMENU_PROMOTE_LOGO_SRC', plugins_url( '/assets/backend/img/logo.jpg', QUADMENU_PLUGIN_FILE ) );
	/**
	 * Notice review
	 */
	define( 'QUADMENU_PROMOTE_REVIEW_URL', 'https://wordpress.org/support/plugin/quadmenu/reviews/?filter=5#new-post' );
	/**
	 * Notice premium sell
	 */
	define( 'QUADMENU_PROMOTE_PREMIUM_SELL_SLUG', 'quadmenu-pro' );
	define( 'QUADMENU_PROMOTE_PREMIUM_SELL_NAME', 'Perfect WooCommerce Brands PRO' );
	define( 'QUADMENU_PROMOTE_PREMIUM_INSTALL_URL', 'https://quadlayers.com/products/quadmenu/?utm_source=qlxxx_admin' );
	define( 'QUADMENU_PROMOTE_PREMIUM_SELL_URL', QUADMENU_PREMIUM_SELL_URL );
	/**
	 * Notice cross sell 1
	 */
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_1_SLUG', 'ai-copilot' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_1_NAME', 'AI Copilot' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_1_DESCRIPTION', esc_html__( 'Boost your productivity in WordPress content creation with AI-driven tools, automated content generation, and enhanced editor utilities.', 'quadmenu' ) );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_1_URL', 'https://quadlayers.com/products/ai-copilot/?utm_source=qlttf_admin' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_1_LOGO_SRC', plugins_url( '/assets/backend/img/ai-copilot.png', QUADMENU_PLUGIN_FILE ) );
	/**
	 * Notice cross sell 2
	 */
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_2_SLUG', 'wp-whatsapp-chat' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_2_NAME', 'Social Chat' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_2_DESCRIPTION', esc_html__( 'Social Chat allows your users to start a conversation from your website directly to your WhatsApp phone number with one click.', 'quadmenu' ) );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_2_URL', 'https://quadlayers.com/products/whatsapp-chat/?utm_source=qlttf_admin' );
	define( 'QUADMENU_PROMOTE_CROSS_INSTALL_2_LOGO_SRC', plugins_url( '/assets/backend/img/wp-whatsapp-chat.jpeg', QUADMENU_PLUGIN_FILE ) );

	new \QuadLayers\WP_Notice_Plugin_Promote\Load(
		QUADMENU_PLUGIN_FILE,
		array(
			array(
				'type'               => 'ranking',
				'notice_delay'       => 0,
				'notice_logo'        => QUADMENU_PROMOTE_LOGO_SRC,
				'notice_description' => sprintf(
								esc_html__( 'Hello! %2$s We\'ve spent countless hours developing this free plugin for you and would really appreciate it if you could drop us a quick rating. Your feedback is extremely valuable to us. %3$s It helps us to get better. Thanks for using %1$s.', 'quadmenu' ),
								'<b>'.QUADMENU_PLUGIN_NAME.'</b>',
								'<span style="font-size: 16px;">🙂</span>',
								'<br>'
				),
				'notice_link'        => QUADMENU_PROMOTE_REVIEW_URL,
				'notice_more_link'   => QUADMENU_SUPPORT_URL,
				'notice_more_label'  => esc_html__(
					'Report a bug',
					'quadmenu'
				),
			),
			array(
				'plugin_slug'        => QUADMENU_PROMOTE_PREMIUM_SELL_SLUG,
				'plugin_install_link'   => QUADMENU_PROMOTE_PREMIUM_INSTALL_URL,
				'plugin_install_label'  => esc_html__(
					'Purchase Now',
					'quadmenu'
				),
				'notice_delay'       => WEEK_IN_SECONDS,
				'notice_logo'        => QUADMENU_PROMOTE_LOGO_SRC,
				'notice_title'       => esc_html__(
					'Hello! We have a special gift!',
					'quadmenu'
				),
				'notice_description' => sprintf(
					esc_html__(
						'Today we have a special gift for you. Use the coupon code %1$s within the next 48 hours to receive a %2$s discount on the premium version of the %3$s plugin.',
						'quadmenu'
					),
					'ADMINPANEL20%',
					'20%',
					QUADMENU_PROMOTE_PREMIUM_SELL_NAME
				),
				'notice_more_link'   => QUADMENU_PROMOTE_PREMIUM_SELL_URL,
			),
			array(
				'plugin_slug'        => QUADMENU_PROMOTE_CROSS_INSTALL_1_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 3,
				'notice_logo'        => QUADMENU_PROMOTE_CROSS_INSTALL_1_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'quadmenu'
					),
					QUADMENU_PROMOTE_CROSS_INSTALL_1_NAME
				),
				'notice_description' => QUADMENU_PROMOTE_CROSS_INSTALL_1_DESCRIPTION,
				'notice_more_link'   => QUADMENU_PROMOTE_CROSS_INSTALL_1_URL
			),
			array(
				'plugin_slug'        => QUADMENU_PROMOTE_CROSS_INSTALL_2_SLUG,
				'notice_delay'       => MONTH_IN_SECONDS * 6,
				'notice_logo'        => QUADMENU_PROMOTE_CROSS_INSTALL_2_LOGO_SRC,
				'notice_title'       => sprintf(
					esc_html__(
						'Hello! We want to invite you to try our %s plugin!',
						'quadmenu'
					),
					QUADMENU_PROMOTE_CROSS_INSTALL_2_NAME
				),
				'notice_description' => QUADMENU_PROMOTE_CROSS_INSTALL_2_DESCRIPTION,
				'notice_more_link'   => QUADMENU_PROMOTE_CROSS_INSTALL_2_URL
			),
		)
	);
}
