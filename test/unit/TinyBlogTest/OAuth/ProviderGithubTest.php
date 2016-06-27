<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\ProviderGithub;
use TinyBlog\OAuth\UserInfo;
use TinyBlog\OAuth\Exception\AuthCodeNotTaken;
use TinyBlog\OAuth\Exception\AccessTokenNotTaken;
use TinyBlog\OAuth\Exception\UserInfoNotTaken;

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
        $request->getQueryParams()->willReturn(['code' => 'test-auth-code']);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $code = $github->grabAuthCode($request->reveal());

        $this->assertEquals('test-auth-code', $code);
    }

    public function testGrabAuthCodeException()
    {
        $this->expectException(AuthCodeNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $request = $this->prophesize(IServerRequest::class);
        $request->getQueryParams()->willReturn([]);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $code = $github->grabAuthCode($request->reveal());
    }

    public function testGetAccessToken()
    {
        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = Response::ok()->withBody('{"access_token":"test-access-token"}');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $token = $github->getAccessToken('test-auth-code');

        $this->assertEquals('test-access-token', $token);
    }

    public function testGetAccessTokenFailResponse()
    {
        $this->expectException(AccessTokenNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = Response::badRequest();
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $token = $github->getAccessToken('test-auth-code');
    }

    public function testGetAccessTokenBrokenResponse()
    {
        $this->expectException(AccessTokenNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');

        $response = Response::ok()->withBody('{"access_token":"test-');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $token = $github->getAccessToken('test-auth-code');
    }

    public function testGetUserInfo()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::ok()->withBody('{"id":123,"name":"FooBar","email":"foo@bar.net"}');
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
        $this->expectException(UserInfoNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::badRequest();
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $user_info = $github->getUserInfo('test-access-token');
    }

    public function testGetUserInfoBrokenResponse()
    {
        $this->expectException(UserInfoNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::ok()->withBody('{"id":123,"name":"FooB');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $github = new ProviderGithub($settings->reveal(), $http_client->reveal());
        $user_info = $github->getUserInfo('test-access-token');
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
