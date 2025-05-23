<?php

declare(strict_types=1);

namespace {

    defined('ABSPATH') or exit;
}

namespace Cdek\Transport {

    use Cdek\Exceptions\External\ApiException;
    use Cdek\Exceptions\External\EntityNotFoundException;
    use Cdek\Exceptions\External\HttpClientException;
    use Cdek\Exceptions\External\HttpServerException;
    use Cdek\Exceptions\External\InvalidRequestException;
    use Cdek\Helpers\Logger;
    use Cdek\Loader;
    use WP_Error;
    use WP_REST_Server;
    use WpOrg\Requests\Utility\CaseInsensitiveDictionary;

    class HttpClient
    {
        private static ?string $correlation = null;

        /**
         * @throws ApiException
         */
        public static function sendJsonRequest(
            string $url,
            string $method,
            ?string $token,
            ?array $data = null,
            ?array $headers = []
        ): HttpResponse {
            $config = [
                'headers' => array_merge([
                    'Content-Type'  => 'application/json',
                    'Authorization' => $token,
                ], $headers),
                'timeout' => 60,
            ];

            if (!empty($data)) {
                $config['body'] = ($method === WP_REST_Server::READABLE) ? $data : wp_json_encode($data);
            }

            $result = self::processRequest($url, $method, $config);

            if (!$result->isSuccess() && $result->getStatusCode() !== 404) {
                Logger::debug(
                    'API returned error',
                    [
                        'code' => $result->getStatusCode(),
                        'resp' => $result->body(),
                    ],
                );
            }

            if ($result->isServerError()) {
                throw new HttpServerException($result->error() ?: ['plain' => $result->body()]);
            }

            if ($result->getStatusCode() === 422) {
                throw new InvalidRequestException($result->error()['fields'], Loader::debug() ? $data : null);
            }

            if ($result->getStatusCode() === 404) {
                throw new EntityNotFoundException($result->error());
            }

            if (!$result->missInvalidLegacyRequest()) {
                throw new InvalidRequestException($result->legacyRequestErrors(), Loader::debug() ? $data : null);
            }

            if ($result->isClientError()) {
                throw new HttpClientException($result->error() ?? []);
            }

            return $result;
        }

        /**
         * @throws ApiException
         */
        public static function processRequest(
            string $url,
            string $method,
            array $config = []
        ): HttpResponse {
            $resp = wp_remote_request($url, array_merge_recursive($config, [
                'headers'    => [
                    'X-App-Name'       => 'wordpress',
                    'X-App-Version'    => Loader::getPluginVersion(),
                    'X-User-Locale'    => get_user_locale(),
                    'X-Correlation-Id' => self::$correlation ??= wp_generate_uuid4(),
                ],
                'method'     => $method,
                'user-agent' => 'wp/'.get_bloginfo('version'),
            ]));

            if (is_wp_error($resp)) {
                assert($resp instanceof WP_Error);
                throw new ApiException([
                    'code' => $resp->get_error_code(),
                    'ip'   => self::tryGetRequesterIp(),
                ], $resp->get_error_message());
            }

            $headers = wp_remote_retrieve_headers($resp);

            return new HttpResponse(
                wp_remote_retrieve_response_code($resp),
                wp_remote_retrieve_body($resp),
                method_exists($headers, 'getAll') ? $headers->getAll() : (array) $headers,
                $url,
                $method,
            );
        }

        public static function tryGetRequesterIp(): ?string
        {
            $ip = wp_remote_retrieve_body(wp_remote_get('https://ipecho.net/plain'));

            if ($ip === '') {
                return null;
            }

            if (!headers_sent()) {
                header("X-Origin-IP: $ip");
            }

            return $ip;
        }
    }
}
