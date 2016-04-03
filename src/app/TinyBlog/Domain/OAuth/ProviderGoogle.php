<?php

namespace TinyBlog\Domain\OAuth;

use Yen\Http\Uri;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IResponse;
use Yen\Http\Request;
use Yen\HttpClient\Contract\IHttpClient;
use Yen\Settings\Contract\ISettings;

class ProviderGoogle implements IProvider
{
    const ID = 2;

    const AUTH_URL     = 'https://accounts.google.com/o/oauth2/v2/auth';
    const TOKEN_URL    = 'https://www.googleapis.com/oauth2/v4/token';
    const USER_API_URL = 'https://www.googleapis.com/userinfo/v2/me';

    protected $client_id;
    protected $client_secret;
    protected $redirect_uri;
    protected $http_client;

    public function __construct(ISettings $settings, IHttpClient $http_client)
    {
        $this->client_id = $settings->get('client_id');
        $this->client_secret = $settings->get('client_secret');
        $this->redirect_uri = $settings->get('redirect_uri');
        $this->http_client = $http_client;
    }

    public function getId()
    {
        return self::ID;
    }

    /**
     * @return IUri
     */
    public function getAuthUrl()
    {
        $url =
            Uri::createFromString(self::AUTH_URL)
                ->withJoinedQuery([
                    'client_id' => $this->client_id,
                    'response_type' => 'code',
                    'redirect_uri' => $this->redirect_uri,
                    'scope' => 'profile'
                ]);

        return $url;
    }

    /**
     * @return string
     */
    public function grabAuthCode(IServerRequest $request)
    {
        $query = $request->getQueryParams();
        return isset($query['code']) ? $query['code'] : '';
    }

    /**
     * @return string
     */
    public function getAccessToken($code)
    {
        $params = http_build_query([
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri
        ]);

        $request = new Request(Uri::createFromString(self::TOKEN_URL));
        $request =
            $request->withMethod(IRequest::METHOD_POST)
                    ->withBody($params);

        $response = $this->http_client->send($request);

        if ($response->getStatusCode() != IResponse::STATUS_OK) {
            return '';
        };

        $json = json_decode($response->getBody());

        if ($json == null) {
            return '';
        };

        return $json->access_token;
    }

    /**
     * @return UserInfo
     */
    public function getUserinfo($access_token)
    {
        $request = new Request(Uri::createFromString(self::USER_API_URL));
        $request =
            $request->withHeader('Authorization', 'Bearer ' . $access_token);

        $response = $this->http_client->send($request);

        if ($response->getStatusCode() != IResponse::STATUS_OK) {
            return new UserInfo(0, '', '');
        };

        $json = json_decode($response->getBody());

        if ($json == null) {
            return new UserInfo(0, '', '');
        };

        return new UserInfo($json->id, $json->name, '');
    }
}
