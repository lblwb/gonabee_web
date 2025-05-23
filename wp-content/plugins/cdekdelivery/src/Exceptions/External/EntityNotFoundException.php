<?php

declare(strict_types=1);

namespace {

    defined('ABSPATH') or exit;
}


namespace Cdek\Exceptions\External {

    class EntityNotFoundException extends HttpClientException
    {
        protected string $key = 'http.missing';

        public function __construct(array $error)
        {
            $this->message = $this->message ?: $error['message'];

            parent::__construct([
                'error' => $error['code'],
            ]);
        }
    }
}
