<?php

// Для авторизованных пользователей
add_action('wp_ajax_prd_info', 'ajax_prd_info');
add_action('wp_ajax_nopriv_prd_info', 'ajax_prd_info');

function ajax_prd_info()
{
	try {
		check_ajax_referer('prd_info_nonce', 'nonce');

		$product_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if (!$product_id) {
			wp_send_json_error(['message' => 'Не передан ID товара']);
		}

		$product = wc_get_product($product_id);
		if (!$product) {
			wp_send_json_error(['message' => 'Товар не найден']);
		}

		// Основные поля
		$prdId = $product->get_id();
		$prdName = $product->get_name();
		$prdDescription = $product->get_description() ? wp_kses_post($product->get_description()) : '';

		// Состав и доставка — из meta (можно заменить на свои ключи)
		$prdComposition =  get_field('cmps_care', $prdId);
		$prdDelivery = get_field('delivery_pay', $prdId);

		// Цвета и размеры — из атрибутов
		try {
			$prdColors = getPrdColors($prdId);
		} catch (Throwable $e) {
			$prdColors = null;
			error_log('Ошибка getPrdColors: ' . $e->getMessage());
		}
		$prdSizes = wc_get_product_terms($product->get_id(), 'pa_sizes', array('fields' => 'names')) ?: null;

		$prdSlidesInfo = getPrdSlides($prdId, $product);

		$prdSlides = $prdSlidesInfo['slides'] ?? [];
		$default_color_slug = $prdSlidesInfo['default_color'] ?? '';

		$prdThumbnail = "";
		$main_thumb_id = $product->get_image_id();
		if ($main_thumb_id) {
			$prdThumbnail = wp_get_attachment_image_url($main_thumb_id, 'original');
		}

		$prdPriceFull = $product->get_price_html();

		$regular_price = $product->get_regular_price();
		$sale_price = $product->get_sale_price();
		$prdPriceSale = '';
		if ($regular_price && $sale_price) {
			$discount = round((($regular_price - $sale_price) / $regular_price) * 100);
			$prdPriceSale = '-' . $discount . '%';
		}

		$prdIsStock = $product->is_in_stock();


		$result = [
			'prdId' => $prdId,
			'prdName' => $prdName,
			'prdDescription' => $prdDescription,
			'prdComposition' => $prdComposition,
			'prdDelivery' => $prdDelivery,
			'prdColors' => $prdColors,
			'prdSizes' => $prdSizes,
			'prdSlides' => $prdSlides,
			'prdDefaultColor' => $default_color_slug,
			'prdThumbnail' => $prdThumbnail,
			'prdPriceFull' => $prdPriceFull,
			'prdPriceSale' => $prdPriceSale,
			'prdIsStock' => $prdIsStock,
		];

		wp_send_json_success($result);
		wp_die();
	} catch (Throwable $e) {
		error_log('ajax_prd_info error: ' . $e->getMessage());
		wp_send_json_error(['message' => 'Ошибка сервера: ' . $e->getMessage()]);
		wp_die();
	}
}

function getPrdColors($prdId)
{
	try {
		$colorList = [];
		if (have_rows('product_colors', $prdId)) {
			while (have_rows('product_colors', $prdId)) {
				the_row();
				$color_rel = get_sub_field('color_rel'); // массив или null
				$color_post = is_array($color_rel) ? $color_rel[0] : null;
				if (!$color_post) continue;
				$color_id = $color_post->ID;
				$color_name = get_the_title($color_id);
				$color_code = get_field('color_code', $color_id);
				$color_slug = get_field('color_slug', $color_id);
				$color_images = get_sub_field('color_images', $color_id);
				$objColor = [
					'color_id' => $color_id,
					'color_name' => $color_name,
					'color_code' => $color_code,
					'color_slug' => $color_slug,
					'color_images' => $color_images,
				];
				$colorList[] = $objColor;
			}
			return $colorList;
		} else {
			return null;
		}
	} catch (Throwable $e) {
		error_log('getPrdColors error: ' . $e->getMessage());
		return null;
	}
}

function getPrdSlides($prdId, $product)
{
	// Новая структура для хранения изображений по цветам
	$images_by_color = [];
	$default_color_slug = '';
	$default_color_code = '';
	$default_color_name = '';

	// 1. Добавляем изображения из галереи WooCommerce как "default" цвет
	$default_images = [];
	$attachment_ids = $product->get_gallery_image_ids();

	if (!empty($attachment_ids)) {
		foreach ($attachment_ids as $attachment_id) {
			$thumb_url = wp_get_attachment_image_url($attachment_id, 'original');
			$default_images[] = esc_url($thumb_url);
		}
	} else {
		// Если галерея пуста — выводим только основное изображение
		$main_thumb_id = $product->get_image_id();
		if ($main_thumb_id) {
			$main_thumb_url = wp_get_attachment_image_url($main_thumb_id, 'original');
			$default_images[] = esc_url($main_thumb_url);
		}
	}

	// 2. Добавляем изображения из ACF поля 'color_images' для каждого цвета
	if (have_rows('product_colors', $prdId)) {
		$color_index = 0;
		while (have_rows('product_colors', $prdId)) {
			the_row();
			$color_rel = get_sub_field('color_rel');
			$color_post = is_array($color_rel) ? $color_rel[0] : null;

			if ($color_post) {
				$color_id = $color_post->ID;
				$color_slug = get_field('color_slug', $color_id);
				$color_name = get_the_title($color_id);
				$color_code = get_field('color_code', $color_id);

				// Устанавливаем первый цвет как дефолтный
				if ($color_index === 0) {
					$default_color_slug = $color_slug;
					$default_color_code = $color_code;
					$default_color_name = $color_name;
				}

				// Получаем изображения для этого цвета
				$color_images = get_sub_field('color_images', $color_id);
				$color_image_urls = [];

				if (!empty($color_images) && is_array($color_images)) {
					foreach ($color_images as $image) {
						if (is_array($image) && isset($image['url'])) {
							$color_image_urls[] = esc_url($image['url']);
						} elseif (is_numeric($image)) {
							$url = wp_get_attachment_image_url($image, 'original');
							if ($url) {
								$color_image_urls[] = esc_url($url);
							}
						} elseif (is_string($image)) {
							$color_image_urls[] = esc_url($image);
						}
					}
				}

				// Если для цвета нет изображений, используем дефолтные
				if (empty($color_image_urls)) {
					$color_image_urls = $default_images;
				}

				$images_by_color[$color_slug] = $color_image_urls;
				$color_index++;
			}
		}
	}

	// Если нет цветов, создаем дефолтную группу
	if (empty($images_by_color)) {
		$images_by_color['default'] = $default_images;
		$default_color_slug = 'default';
	}

	return ['slides' => $images_by_color, 'default_color' => $default_color_slug];
}
