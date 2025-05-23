<?php

declare(strict_types=1);

namespace {

    defined('ABSPATH') or exit;
}

namespace Cdek\Actions {

    use Cdek\CdekApi;
    use Cdek\Config;
    use Cdek\Traits\CanBeCreated;

    class GenerateWaybillAction
    {
        use CanBeCreated;

        /**
         * @throws \Cdek\Exceptions\External\LegacyAuthException
         * @throws \Cdek\Exceptions\External\ApiException
         */
        public function __invoke(string $cdekNumber): array
        {
            $api = new CdekApi;

            ini_set(
                'max_execution_time',
                (string)(30 +
                Config::GRAPHICS_FIRST_SLEEP +
                Config::GRAPHICS_TIMEOUT_SEC * Config::MAX_REQUEST_RETRIES_FOR_GRAPHICS),
            );

            $order = $api->orderGetByNumber($cdekNumber);

            if ($order->entity() === null) {
                return [
                    'success' => false,
                    'message' => esc_html__(
                        "Failed to create waybill.\nTo solve the problem, try re-creating the order.\nYou may need to cancel existing one (if that button exists)",
                        'cdekdelivery',
                    ),
                ];
            }

            foreach ($order->related() as $entity) {
                if ($entity['type'] === 'waybill' && isset($entity['url'])) {
                    return [
                        'success' => true,
                        'data'    => esc_html(base64_encode($api->fileGetRaw($entity['url']))),
                    ];
                }
            }

            $waybill = $api->waybillCreate($cdekNumber);

            if ($waybill === null) {
                return [
                    'success' => false,
                    'message' => esc_html__(
                        "Failed to create waybill.\nTry re-creating the order.\nYou may need to cancel existing one (if that button exists)",
                        'cdekdelivery',
                    ),
                ];
            }

            sleep(Config::GRAPHICS_FIRST_SLEEP);

            for ($i = 0; $i < Config::MAX_REQUEST_RETRIES_FOR_GRAPHICS; $i++) {
                $waybillInfo = $api->waybillGet($waybill);

                if (isset($waybillInfo['url'])) {
                    return [
                        'success' => true,
                        'data'    => esc_html(base64_encode($api->fileGetRaw($waybillInfo['url']))),
                    ];
                }

                if ($waybillInfo === null || end($waybillInfo['statuses'])['code'] === 'INVALID') {
                    return [
                        'success' => false,
                        'message' => esc_html__("Failed to create waybill.\nTry again", 'cdekdelivery'),
                    ];
                }

                sleep(Config::GRAPHICS_TIMEOUT_SEC);
            }

            return [
                'success' => false,
                'message' => esc_html__(
                    "A request for a waybill was sent, but no response was received.\nWait for 1 hour before trying again",
                    'cdekdelivery',
                ),
            ];
        }
    }
}
