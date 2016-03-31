<?php

namespace TinyBlog\Domain\OAuth;

use Yen\Http\Uri;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IResponse;
use Yen\Http\Request;
use Yen\HttpClient\Contract\IHttpClient;
use Yen\Settings\Contract\ISettings;

class ProviderGithub implements IProvider
{
    const ID = 1;

    const AUTH_URL     = 'https://github.com/login/oauth/authorize';
    const TOKEN_URL    = 'https://github.com/login/oauth/access_token';
    const USER_API_URL = 'https://api.github.com/user';

    protected $client_id;
    protected $client_secret;
    protected $http_client;

    public function __construct(ISettings $settings, IHttpClient $http_client)
    {
        $this->client_id = $settings->get('client_id');
        $this->client_secret = $settings->get('client_secret');
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
                    'client_id' => $this->client_id
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
            'code' => $code
        ]);

        $request = new Request(Uri::createFromString(self::TOKEN_URL));
        $request =
            $request->withMethod(IRequest::METHOD_POST)
                    ->withHeader('Accept', 'application/json')
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
            $request->withHeader('Authorization', 'token ' . $access_token)
                    ->withHeader('Accept', 'application/json');

        $response = $this->http_client->send($request);

        if ($response->getStatusCode() != IResponse::STATUS_OK) {
            return new UserInfo(0, '', '');
        };

        $json = json_decode($response->getBody());

        if ($json == null) {
            return new UserInfo(0, '', '');
        };

        return new UserInfo($json->id, $json->name, $json->email);
    }
}
