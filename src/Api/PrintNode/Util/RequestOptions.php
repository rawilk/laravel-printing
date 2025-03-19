<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Util;

use Illuminate\Support\Str;
use Rawilk\Printing\Exceptions\InvalidArgument;

class RequestOptions
{
    public function __construct(
        public ?string $apiKey = null,
        public array $headers = [],
        public ?string $apiBase = null,
    ) {
    }

    public function __debugInfo(): ?array
    {
        return [
            'apiKey' => $this->redactedApiKey(),
            'headers' => $this->headers,
            'apiBase' => $this->apiBase,
        ];
    }

    /**
     * Unpacks an options array into a RequestOptions object.
     *
     * @param  bool  $strict  when true, forbid string form and arbitrary keys in array form
     */
    public static function parse(RequestOptions|array|string|null $options, bool $strict = false): self
    {
        if ($options instanceof self) {
            return clone $options;
        }

        if ($options === null) {
            return new self(null, [], null);
        }

        if (is_string($options)) {
            throw_if(
                $strict,
                InvalidArgument::class,
                <<<'TXT'
                Do not pass a string for request options. If you want to set
                the API key, pass an array like ["api_key" => <apiKey>] instead.
                TXT
            );

            return new self($options, [], null);
        }

        if (is_array($options)) {
            $headers = [];
            $key = null;
            $base = null;

            if (array_key_exists('api_key', $options)) {
                $key = $options['api_key'];
                unset($options['api_key']);
            }

            if (array_key_exists('idempotency_key', $options)) {
                $headers['X-Idempotency-Key'] = $options['idempotency_key'];
                unset($options['idempotency_key']);
            }

            if (array_key_exists('api_base', $options)) {
                $base = $options['api_base'];
                unset($options['api_base']);
            }

            if ($strict && ! empty($options)) {
                $message = 'Got unexpected keys in options array: ' . implode(', ', array_keys($options));

                throw new InvalidArgument($message);
            }

            return new self($key, $headers, $base);
        }

        throw new InvalidArgument('Unexpected value received for request options.');
    }

    /**
     * Unpacks an options array and merges it into the existing RequestOptions object.
     *
     * @param  bool  $strict  when true, forbid string form and arbitrary keys in array form
     */
    public function merge(RequestOptions|array|null|string $options, bool $strict = false): self
    {
        $otherOptions = self::parse($options, $strict);
        if ($otherOptions->apiKey === null) {
            $otherOptions->apiKey = $this->apiKey;
        }

        if ($otherOptions->apiBase === null) {
            $otherOptions->apiBase = $this->apiBase;
        }

        $otherOptions->headers = array_merge($this->headers, $otherOptions->headers);

        return $otherOptions;
    }

    private function redactedApiKey(): string
    {
        if ($this->apiKey === null) {
            return '';
        }

        return Str::mask($this->apiKey, '*', 4);
    }
}
