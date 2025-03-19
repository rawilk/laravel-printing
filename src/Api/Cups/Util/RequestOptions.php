<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Util;

use Illuminate\Support\Str;
use Rawilk\Printing\Api\Cups\Cups;
use Rawilk\Printing\Exceptions\InvalidArgument;
use SensitiveParameter;

class RequestOptions
{
    public function __construct(
        public ?string $ip = null,
        public ?string $username = null,
        #[SensitiveParameter] public ?string $password = null,
        public ?int $port = null,
        public ?bool $secure = null,
        public array $headers = [],
    ) {
    }

    public function __debugInfo(): ?array
    {
        return [
            'ipAddress' => $this->ip,
            'username' => $this->username,
            'password' => $this->redactedPassword(),
            'port' => $this->port,
            'secure' => $this->secure,
            'headers' => $this->headers,
        ];
    }

    /**
     * Unpacks an options array into a RequestOptions object.
     *
     * @param  bool  $strict  when true, forbid arbitrary keys in array form
     */
    public static function parse(RequestOptions|array|null $options, bool $strict = false): self
    {
        if ($options instanceof self) {
            return clone $options;
        }

        if ($options === null) {
            return new self(ip: null, username: null, password: null, headers: []);
        }

        if (is_array($options)) {
            $headers = [];
            $ip = null;
            $username = null;
            $password = null;
            $port = null;
            $secure = null;

            if (array_key_exists('ip', $options)) {
                $ip = $options['ip'];
                unset($options['ip']);
            }

            if (array_key_exists('username', $options)) {
                $username = $options['username'];
                unset($options['username']);
            }

            if (array_key_exists('password', $options)) {
                $password = $options['password'];
                unset($options['password']);
            }

            if (array_key_exists('port', $options)) {
                $port = $options['port'];
                unset($options['port']);
            }

            if (array_key_exists('secure', $options)) {
                $secure = $options['secure'];
                unset($options['secure']);
            }

            if ($strict && ! empty($options)) {
                $message = 'Got unexpected keys in options array: ' . implode(', ', array_keys($options));

                throw new InvalidArgument($message);
            }

            return new self(
                ip: $ip,
                username: $username,
                password: $password,
                port: $port,
                secure: $secure,
                headers: $headers,
            );
        }

        throw new InvalidArgument('Unexpected value received for cups request options.');
    }

    /**
     * Unpacks an options array and merges it into the existing RequestOptions object.
     *
     * @param  bool  $strict  when true, forbid arbitrary keys in array form
     */
    public function merge(RequestOptions|array|null $options, bool $strict = false): self
    {
        $otherOptions = self::parse($options, $strict);
        if ($otherOptions->ip === null) {
            $otherOptions->ip = $this->ip;
        }

        if ($otherOptions->username === null) {
            $otherOptions->username = $this->username;
        }

        if ($otherOptions->password === null) {
            $otherOptions->password = $this->password;
        }

        if ($otherOptions->port === Cups::DEFAULT_PORT) {
            $otherOptions->port = $this->port;
        }

        if ($otherOptions->secure === Cups::DEFAULT_SECURE) {
            $otherOptions->secure = $this->secure;
        }

        $otherOptions->headers = array_merge($this->headers, $otherOptions->headers);

        return $otherOptions;
    }

    private function redactedPassword(): ?string
    {
        if ($this->password === null) {
            return null;
        }

        return Str::mask($this->password, '*', 0);
    }
}
