<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Rawilk\Printing\Api\PrintNode\Exceptions\AuthenticationFailure;
use Rawilk\Printing\Api\PrintNode\Exceptions\UnexpectedValue;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Api\PrintNode\Util\Util;
use SensitiveParameter;

/**
 * Note: This client is inspired from Stripe's php sdk client.
 */
class BasePrintNodeClient implements PrintNodeClientInterface
{
    use Conditionable;
    use Macroable;

    public const API_BASE = 'https://api.printnode.com';

    private const DEFAULT_CONFIG = [
        'api_key' => null,
        'api_base' => self::API_BASE,
    ];

    private array $config;

    private RequestOptions $defaultOpts;

    public function __construct(#[SensitiveParameter] string|array|null $config = [])
    {
        if (is_string($config)) {
            $config = ['api_key' => $config];
        } elseif (! is_array($config)) {
            throw new InvalidArgumentException('$config must be a string or an array');
        }

        $config = array_merge(self::DEFAULT_CONFIG, $config);
        $this->guardAgainstInvalidConfig($config);

        $this->config = $config;

        $this->defaultOpts = RequestOptions::parse([
            'api_key' => PrintNode::getApiKey(),
        ]);
    }

    public function getApiKey(): ?string
    {
        return $this->config['api_key'];
    }

    public function getApiBase(): string
    {
        return $this->config['api_base'];
    }

    public function setApiKey(string $apiKey): static
    {
        $this->config['api_key'] = $apiKey;

        return $this;
    }

    public function request(
        string $method,
        string $path,
        array $params = [],
        array|RequestOptions $opts = [],
        ?string $expectedResource = null,
    ) {
        $defaultRequestOpts = $this->defaultOpts;

        $opts = $defaultRequestOpts->merge($opts, true);

        $baseUrl = $opts->apiBase ?: $this->getApiBase();

        $requestor = new PrintNodeApiRequestor(
            $this->apiKeyForRequest($opts),
            $baseUrl,
        );

        /** @var \Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse $response */
        [$opts->apiKey, $response] = $requestor->request($method, $path, $params, $opts->headers);

        $obj = Util::convertToPrintNodeObject($response->body, $opts, $expectedResource);

        if ($obj instanceof PrintNodeObject) {
            $obj->setLastResponse($response);
        } elseif (is_array($obj)) {
            foreach ($obj as $resource) {
                if (! $resource instanceof PrintNodeObject) {
                    continue;
                }

                $resource->setLastResponse($response);
            }
        }

        return $obj;
    }

    public function requestCollection(
        string $method,
        string $path,
        array $params = [],
        RequestOptions|array $opts = [],
        ?string $expectedResource = null,
    ): Collection {
        $resources = $this->request($method, $path, $params, $opts, $expectedResource);

        throw_unless(
            is_array($resources),
            UnexpectedValue::class,
            'Expected to receive array from the PrintNode API.',
        );

        return collect($resources);
    }

    private function apiKeyForRequest(RequestOptions $opts): string
    {
        $apiKey = $opts->apiKey ?? $this->getApiKey() ?? PrintNode::getApiKey();

        throw_if(
            blank($apiKey),
            AuthenticationFailure::class,
            <<<'TXT'
            No API key provided. Set your API when constructing the
            PrintNodeClient instance, or provide it on a per-request
            basis using the `api_key` key in the $opts argument.
            TXT
        );

        return $apiKey;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    private function guardAgainstInvalidConfig(array $config): void
    {
        // api key
        throw_if(
            $config['api_key'] !== null && ! is_string($config['api_key']),
            InvalidArgumentException::class,
            'api_key must be null or a string',
        );

        throw_if(
            $config['api_key'] !== null && ($config['api_key'] === ''),
            InvalidArgumentException::class,
            'api_key cannot be an empty string',
        );

        throw_if(
            $config['api_key'] !== null && (preg_match('/\s/', $config['api_key'])),
            InvalidArgumentException::class,
            'api_key cannot contain whitespace',
        );

        // api base
        throw_unless(
            is_string($config['api_base']),
            InvalidArgumentException::class,
            'api_base must be a string',
        );

        // Check absence of extra keys
        $extraConfigKeys = array_diff(array_keys($config), array_keys(self::DEFAULT_CONFIG));
        throw_if(
            filled($extraConfigKeys),
            InvalidArgumentException::class,
            'Found unknown key(s) in configuration array: ' . "'" . implode("', '", $extraConfigKeys) . "'",
        );
    }
}
