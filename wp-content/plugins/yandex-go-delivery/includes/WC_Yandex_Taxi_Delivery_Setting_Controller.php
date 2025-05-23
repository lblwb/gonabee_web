<?php

defined( 'ABSPATH' ) || exit;

use WCYandexTaxiDeliveryPlugin\Constants;
use WCYandexTaxiDeliveryPlugin\AvailableTariffChecker;
use YandexTaxi\Delivery\YandexApi\Exceptions\YandexApiException;
use YandexTaxi\Delivery\YandexApi\Exceptions\NotAuthorizedException;

/**
 * Class WC_Yandex_Taxi_Delivery_Setting_Controller
 */
class WC_Yandex_Taxi_Delivery_Setting_Controller extends WC_Yandex_Taxi_Delivery_Base_Controller {
	public static function index() {
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
			self::store();
		}

		$settings = self::get_settings();

		self::renderView( 'settings/edit', [
			'settings' => $settings,
			'config'   => self::get_config(),
			'message'  => self::get_notice_message(),
		] );
	}

	public static function get_translations() {
		self::renderJson( [
			'send_to_button' => __( 'Отправить в Яндекс Доставку', 'yandex-go-delivery' ),
			'select_order'   => __( 'Выбeрите хотя бы один заказ для отправки в доставку', 'yandex-go-delivery' ),
		] );
	}

	private static function store() {
		$data = wc_clean( $_REQUEST );
		$data = self::prepare_package_default_params( $data );

		WC_Admin_Settings::save_fields( self::prepare_config(), [ YGO_PLUGIN_SETTINGS => $data ] );
	}

	private static function get_notice_message(): ?string {
		$settings = get_option( YGO_PLUGIN_SETTINGS, [
			'token'         => '',
			'country'       => '',
			'geocode_token' => '',
			'geocoder'      => 'yandex',
		] );

		$error = [];

		if ( empty( $settings['token'] ) ) {
			$error[] = __( 'Токен API Доставки не введён.', 'yandex-go-delivery' );
		}

		if ( 'Chile' !== $settings['country'] && empty( $settings['geocode_token'] ) ) {
			$error[] = __( 'Токен API Геосервиса не введён.', 'yandex-go-delivery' );
		}

		if ( ! empty( $settings['geocode_token'] ) ) {
			$token_error_message = __( 'Токен API Геосервиса введён не верно. Проверьте токен.', 'yandex-go-delivery' );

			switch ( $settings['geocoder'] ) {
				case 'yandex':
					$response = wp_remote_get( "https://geocode-maps.yandex.ru/1.x/?apikey={$settings['geocode_token']}&format=json&geocode=Астрахань+Ленина+1" );
					if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
						$body = json_decode( wp_remote_retrieve_body( $response ), true );
						if ( $body && isset( $body['error'] ) ) {
							$error[] = $token_error_message;
						}
					}
					break;
				case 'google':
					$response = wp_remote_get( "https://maps.googleapis.com/maps/api/geocode/json?key={$settings['geocode_token']}&address=Москва+Тверская+6" );
					$body     = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( $body && isset( $body['error_message'] ) ) {
						$error[] = $token_error_message;
					}
					break;
			}
		}

		if ( ! empty( $error ) ) {
			return self::getView( 'partial/_error', [ 'error' => implode( '<br>', $error ) ] );
		}

		WC_Yandex_Taxi_Delivery_App::register_services();

		try {
			if ( AvailableTariffChecker::isAvailable() ) {
				return null;
			}

			return self::getView( 'partial/_error', [ 'error' => self::getView( 'partial/_no_tariffs' ) ] );
		} catch ( NotAuthorizedException $exception ) {
			return self::getView( 'partial/_error', [ 'error' => self::getView( 'partial/_bad_token' ) ] );
		} catch ( YandexApiException $exception ) {
			return self::getView( 'partial/_error', [ 'error' => $exception->getMessage() ] );
		}
	}

	private static function prepare_package_default_params( array $data ): array {
		$weightKey          = 'default_weight';
		$data[ $weightKey ] = self::get_setting_value_or_min( $data, $weightKey, 0.1 );

		$dimensions = [
			'width',
			'length',
			'height',
		];

		foreach ( $dimensions as $dimension ) {
			$key          = 'default_' . $dimension;
			$data[ $key ] = self::get_setting_value_or_min( $data, $key, 1 );
		}

		return $data;
	}

	private static function get_setting_value_or_min( array $data, string $key, float $min ) {
		if ( ! isset( $data[ $key ] ) ) {
			return $min;
		}

		$value = (float) $data[ $key ];

		if ( $value < $min ) {
			return $min;
		}

		return $value;
	}

	public static function get_settings(): array {
		$settings = get_option( YGO_PLUGIN_SETTINGS );
		if ( ! $settings ) {
			$settings = self::get_default_config();
		}

		return $settings;
	}

	private static function get_assembly_minutes(): array {
		$minutes = [];

		foreach ( range( 10, 100, 10 ) as $step ) {
			$minutes[ $step ] = $step . ' минут';
		}

		return $minutes;
	}

	private static function prepare_config(): array {
		$result = [];

		foreach ( self::get_config() as $key => $param ) {
			$result[] = array_merge( [ 'id' => YGO_PLUGIN_SETTINGS . '[' . $key . ']' ], $param );
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public static function get_default_config(): array {
		$settings = [];
		foreach ( self::get_config( true ) as $name => $option ) {
			$settings[ $name ] = $option['default'] ?? '';
		}

		return $settings;
	}

	private static function get_config( $default = false ): array {
		return [
			'country'                  => [
				'title'   => __( 'Выберите страну', 'yandex-go-delivery' ),
				'type'    => 'select',
				'options' => [
					'Russia'     => 'Russia',
					//'Israel' => 'Israel',
					'Belarus'    => 'Belarus',
					'Kazakhstan' => 'Kazakhstan',
					'Uzbekistan' => 'Узбекистан',
					'Armenia'    => 'Армения',
					//'Chile' => 'Chile',
					//'SouthAfrica' => 'Republic of South Africa',
				],
			],
			'token'                    => [
				'title'       => __( 'Токен API Яндекс Доставки', 'yandex-go-delivery' ),
				'type'        => 'text',
				'description' => __( 'API токен из личного кабинета Яндекс Доставки', 'yandex-go-delivery' ),
			],
			'geocoder'                 => [
				'title'   => __( 'Геосервис', 'yandex-go-delivery' ),
				'type'    => 'select',
				'options' => [
					'yandex' => 'Яндекс.Геосервисы',
					'google' => 'Google Geocoding',
					/*'dadata' => 'Dadata Геокодирование',*/
				],
			],
			'geocode_token'            => [
				'title'       => __( 'Токен API выбранного геосервиса', 'yandex-go-delivery' ),
				'type'        => 'text',
				'description' => '',
			],
			'inn'                      => [
				'title'             => __( 'ИНН магазина', 'yandex-go-delivery' ),
				'type'              => 'text',
				'description'       => __( 'ИНН интернет-магазина (10 или 12 цифр)', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'maxlength' => '12',
					'pattern'   => '^(\d{9}|\d{12})$',
				],
			],
			'vat'                      => [
				'title'   => __( 'Ставка НДС', 'yandex-go-delivery' ),
				'type'    => 'select',
				'options' => [
					'vat0'  => '0%',
					'vat10' => '10%',
					'vat18' => '18%',
					'vat20' => '20%',
				],
			],
			'auto_change_status'       => [
				'title'   => __( 'Автоматически изменять статус на "Выполнен", когда заказ доставлен', 'yandex-go-delivery' ),
				'type'    => 'checkbox',
				'default' => 'no',
			],
			'assembly_delay_minutes'   => [
				'title'       => __( 'Время сборки заказа', 'yandex-go-delivery' ),
				'type'        => 'select',
				'options'     => ! $default ? self::get_assembly_minutes() : [],
				'description' => __( 'Время от момента оформления заказа пользователем до момента подачи машины на склад', 'yandex-go-delivery' ),
				'display'     => false,
			],
			'default_weight'           => [
				'title'             => __( 'Вес товара по умолчанию', 'yandex-go-delivery' ),
				'type'              => 'decimal',
				'description'       => __( 'Вес товара в кг, минимальное значение 0.1 кг', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 0.1,
				],
			],
			'default_width'            => [
				'title'             => __( 'Ширина товара по умолчанию', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Ширина товара в см, минимальное значение 1 см', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 1,
				],
			],
			'default_length'           => [
				'title'             => __( 'Длина товара по умолчанию', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Длина товара в см, минимальное значение 1 см', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 1,
				],
			],
			'default_height'           => [
				'title'             => __( 'Высота товара по умолчанию', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Высота товара в см, минимальное значение 1 см', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 1,
				],
			],
			'enabled'                  => [
				'title'       => __( 'Включено в корзине', 'yandex-go-delivery' ),
				'type'        => 'checkbox',
				'description' => __( 'В корзине рассчитывается стоимость для срочной доставки на ближайшее время. Если между расчетом стоимости и отправкой заказа в доставку пройдет много времени, стоимость может существенно измениться.', 'yandex-go-delivery' ),
				'default'     => 'no',
			],
			'post_payment'             => [
				'title'       => __( 'Оплата при получении', 'yandex-go-delivery' ),
				'type'        => 'checkbox',
				'description' => __( 'Оплата при получении разрешает покупателям выбирать способы оплаты при получении на странице оформления заказа. <br><u>Для вашего аккаунта <b>Яндекс Доставка</b> должна быть подключена эта услуга! Обратитесь к менеджеру.</u>', 'yandex-go-delivery' ),
				'default'     => 'no',
			],
			'payment_methods'          => [
				'title'       => __( 'Выберите способы оплаты, возможные при выборе Яндекс Доставки', 'yandex-go-delivery' ),
				'type'        => 'multiselect',
				'options'     => ! $default ? self::get_payment_method_list() : [],
				'description' => '',
			],
			'payment_method_label'     => [
				'title'   => __( 'Выберите название Яндекс Доставки в корзине', 'yandex-go-delivery' ),
				'type'    => 'select',
				'options' => [
					'delivery'         => __( 'Яндекс Доставка', 'yandex-go-delivery' ),
					'express_delivery' => __( 'Экспресс Яндекс Доставка', 'yandex-go-delivery' ),
					'yango_delivery'   => 'Yango Delivery',
				],
			],
			'use_order_price_for_free' => [
				'title'       => __( 'Использовать настройку "Бесплатная доставка от"', 'yandex-go-delivery' ),
				'type'        => 'checkbox',
				'default'     => 'no',
				'description' => __( 'Включить "Бесплатную доставку от"', 'yandex-go-delivery' ),
			],
			'order_price_for_free'     => [
				'title'       => __( 'Бесплатная доставка от:', 'yandex-go-delivery' ),
				'type'        => 'number',
				'description' => __( 'Сумма заказа, от которой Доставка Яндекс Доставка будет для пользователя бесплатной', 'yandex-go-delivery' ),
			],
			'fixed_price_is_on'        => [
				'title'   => __( 'Включена ли фиксированная  стоимость Яндекс Доставки', 'yandex-go-delivery' ),
				'type'    => 'checkbox',
				'default' => 'no',
			],
			'fixed_price'              => [
				'title'             => __( 'Сумма фиксированной стоимости Яндекс Доставки', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Сумма фиксированной стоимости Яндекс Доставки', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 0,
				],
			],
			'price_markup'             => [
				'title'             => __( 'Наценка на доставку, %', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Процент наценки на стоимость Яндекс Доставки', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 0,
				],
			],
			'discount_is_on'           => [
				'title'   => __( 'Включена ли скидка на Яндекс Доставку', 'yandex-go-delivery' ),
				'type'    => 'checkbox',
				'default' => 'no',
			],
			'discount_size'            => [
				'title'             => __( 'Скидка на доставку, %', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Процент скидки на стоимость Яндекс Доставки', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 0,
				],
			],
			'discount_from_price'      => [
				'title'             => __( 'Стоимость заказа, от которого применять скидку', 'yandex-go-delivery' ),
				'type'              => 'number',
				'description'       => __( 'Стоимость заказа, от которого применять скидку', 'yandex-go-delivery' ),
				'custom_attributes' => [
					'min' => 0,
				],
			],
			'bulk_send_to_delivery'    => [
				'title'       => __( 'Массовая отправка заказов', 'yandex-go-delivery' ),
				'type'        => 'checkbox',
				'description' => __( 'Отправлять каждый заказ по отдельности при массовом выделении в списке заказов', 'yandex-go-delivery' ),
				'default'     => 'no',
			],

		];
	}

	private static function get_payment_method_list(): array {
		$gateways = WC()->payment_gateways->get_available_payment_gateways();
		$list     = [];
		$settings = ygo_get_settings();

		if ( $gateways ) {
			foreach ( $gateways as $gateway ) {
				$disabled = false;
				if ( 'yes' !== $settings['post_payment'] && in_array( $gateway->id, apply_filters( 'yandex_go/post_payment_methods', [] ) ) ) {
					$disabled = true;
				}
				if ( 'yes' === $gateway->enabled ) {
					$list[] = [
						'value'    => $gateway->id,
						'title'    => $gateway->title,
						'disabled' => $disabled,
					];
				}
			}
		}

		return $list;
	}
}
