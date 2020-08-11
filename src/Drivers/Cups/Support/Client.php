<?php

namespace Rawilk\Printing\Drivers\Cups\Support;

use GuzzleHttp\Psr7\Uri;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\DecoderPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Client\Socket\Client as SocketHttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Smalot\Cups\CupsException;

/*
 * This class is here for now as a workaround since the declaration
 * of sendRequest() is not compatible with the interface in the
 * dependency and has not been updated yet.
 */
class Client implements HttpClient
{
    const SOCKET_URL = 'unix:///var/run/cups/cups.sock';

    const AUTHTYPE_BASIC = 'basic';

    const AUTHTYPE_DIGEST = 'digest';

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $authType;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * Client constructor.
     *
     * @param string $username
     * @param string $password
     * @param array $socketClientOptions
     */
    public function __construct($username = null, $password = null, $socketClientOptions = [])
    {
        if (! is_null($username)) {
            $this->username = $username;
        }

        if (! is_null($password)) {
            $this->password = $password;
        }

        if (empty($socketClientOptions['remote_socket'])) {
            $socketClientOptions['remote_socket'] = self::SOCKET_URL;
        }

        $messageFactory = new GuzzleMessageFactory();
        $socketClient = new SocketHttpClient($messageFactory, $socketClientOptions);
        $host = preg_match(
            '/unix:\/\//',
            $socketClientOptions['remote_socket']
        ) ? 'http://localhost' : $socketClientOptions['remote_socket'];
        $this->httpClient = new PluginClient(
            $socketClient,
            [
                new ErrorPlugin(),
                new ContentLengthPlugin(),
                new DecoderPlugin(),
                new AddHostPlugin(new Uri($host)),
            ]
        );

        $this->authType = self::AUTHTYPE_BASIC;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return \Smalot\Cups\Transport\Client
     */
    public function setAuthentication($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @param string $authType
     *
     * @return $this
     */
    public function setAuthType($authType)
    {
        $this->authType = $authType;

        return $this;
    }

    /**
     * (@inheritdoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->username || $this->password) {
            switch ($this->authType) {
                case self::AUTHTYPE_BASIC:
                    $pass = base64_encode($this->username.':'.$this->password);
                    $authentication = 'Basic '.$pass;

                    break;

                case self::AUTHTYPE_DIGEST:
                    throw new CupsException('Auth type not supported');

                default:
                    throw new CupsException('Unknown auth type');
            }

            $request = $request->withHeader('Authorization', $authentication);
        }

        return $this->httpClient->sendRequest($request);
    }
}
