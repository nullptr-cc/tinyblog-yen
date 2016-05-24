<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\UserInfo;
use Yen\Http\Contract\IUri;
use Yen\Http\Contract\IServerRequest;
use Yen\Http\Contract\IRequest;
use Yen\Http\Contract\IResponse;
use Yen\Http\Response;
use Yen\HttpClient\Contract\IHttpClient;
use Yen\Settings\Contract\ISettings;
use Prophecy\Argument;

class ProviderGithubTest extends \PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());

        $this->assertEquals(1, $github->getId());
    }

    public function testGetAuthUrl()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $auth_url = $github->getAuthUrl();

        $this->assertInstanceOf(IUri::class, $auth_url);
        $this->assertEquals(
            'https://github.com/login/oauth/authorize?client_id=test-client-id',
            $auth_url->__toString()
        );
    }

    public function testGrabAuthCode()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $request = $this->prophesize(IServerRequest::class);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());

        $request->getQueryParams()->willReturn(['code' => 'test-auth-code']);
        $this->assertEquals('test-auth-code', $github->grabAuthCode($request->reveal()));

        $request->getQueryParams()->willReturn([]);
        $this->assertEquals('', $github->grabAuthCode($request->reveal()));
    }

    public function testGetAccessToken()
    {
        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = new Response(IResponse::STATUS_OK, [], '{"access_token":"test-access-token"}');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());

        $this->assertEquals('test-access-token', $github->getAccessToken('test-auth-code'));
    }

    public function testGetAccessTokenFailResponse()
    {
        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = new Response(IResponse::STATUS_BAD_REQUEST, [], '');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());

        $this->assertEquals('', $github->getAccessToken('test-auth-code'));
    }

    public function testGetAccessTokenBrokenResponse()
    {
        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = new Response(IResponse::STATUS_OK, [], '{"access_token":"test-');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());

        $this->assertEquals('', $github->getAccessToken('test-auth-code'));
    }

    public function testGetUserInfo()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = new Response(IResponse::STATUS_OK, [], '{"id":123,"name":"FooBar","email":"foo@bar.net"}');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $user_info = $github->getUserInfo('test-access-token');

        $this->assertInstanceOf(UserInfo::class, $user_info);
        $this->assertEquals(123, $user_info->identifier());
        $this->assertEquals('FooBar', $user_info->name());
        $this->assertEquals('foo@bar.net', $user_info->email());
    }

    public function testGetUserInfoFailResponse()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = new Response(IResponse::STATUS_BAD_REQUEST, [], '');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $user_info = $github->getUserInfo('test-access-token');

        $this->assertInstanceOf(UserInfo::class, $user_info);
        $this->assertEquals(0, $user_info->identifier());
        $this->assertEquals('', $user_info->name());
        $this->assertEquals('', $user_info->email());
    }

    public function testGetUserInfoBrokenResponse()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = new Response(IResponse::STATUS_OK, [], '{"id":123,"name":"FooB');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $user_info = $github->getUserInfo('test-access-token');

        $this->assertInstanceOf(UserInfo::class, $user_info);
        $this->assertEquals(0, $user_info->identifier());
        $this->assertEquals('', $user_info->name());
        $this->assertEquals('', $user_info->email());
    }

    public function prpCheckTokenRequest(IRequest $request)
    {
        $eurl = 'https://github.com/login/oauth/access_token';
        $ebody = 'client_id=test-client-id&' .
                 'client_secret=test-client-secret&' .
                 'code=test-auth-code';

        if ($request->getUri()->__toString() != $eurl) {
            return false;
        };

        if ($request->getMethod() != IRequest::METHOD_POST) {
            return false;
        };

        if (!$request->hasHeader('Accept')) {
            return false;
        };

        if ($request->getHeader('Accept') != 'application/json') {
            return false;
        };

        if ($request->getBody() != $ebody) {
            return false;
        };

        return true;
    }

    public function prpCheckUserInfoRequest(IRequest $request)
    {
        $eurl = 'https://api.github.com/user';

        if ($request->getUri()->__toString() != $eurl) {
            return false;
        };

        if ($request->getMethod() != IRequest::METHOD_GET) {
            return false;
        };

        if (!$request->hasHeader('Accept')) {
            return false;
        };

        if ($request->getHeader('Accept') != 'application/json') {
            return false;
        };

        if (!$request->hasHeader('Authorization')) {
            return false;
        };

        if ($request->getHeader('Authorization') != 'token test-access-token') {
            return false;
        };

        return true;
    }
}
