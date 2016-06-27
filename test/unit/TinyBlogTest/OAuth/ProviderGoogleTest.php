<?php

namespace TinyBlogTest\OAuth;

use TinyBlog\OAuth\ProviderGoogle;
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

class ProviderGoogleTest extends \PHPUnit_Framework_TestCase
{
    public function testGetId()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $github = new ProviderGoogle($settings->reveal(), $http_client->reveal());

        $this->assertEquals(2, $github->getId());
    }

    public function testGetAuthUrl()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');
        $settings->get('redirect_uri')->willReturn('test-redirect-uri');

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $auth_url = $google->getAuthUrl();

        $eurl = 'https://accounts.google.com/o/oauth2/v2/auth?' .
                'client_id=test-client-id&' .
                'response_type=code&' .
                'redirect_uri=test-redirect-uri&' .
                'scope=profile';
        $this->assertInstanceOf(IUri::class, $auth_url);
        $this->assertEquals($eurl, $auth_url->__toString());
    }

    public function testGrabAuthCode()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $request = $this->prophesize(IServerRequest::class);
        $request->getQueryParams()->willReturn(['code' => 'test-auth-code']);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $code = $google->grabAuthCode($request->reveal());

        $this->assertEquals('test-auth-code', $code);
    }

    public function testGrabAuthCodeException()
    {
        $this->expectException(AuthCodeNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);
        $request = $this->prophesize(IServerRequest::class);
        $request->getQueryParams()->willReturn();

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $code = $google->grabAuthCode($request->reveal());
    }

    public function testGetAccessToken()
    {
        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');
        $settings->get('redirect_uri')->willReturn('test-redirect-uri');

        $response = Response::ok()->withBody('{"access_token":"test-access-token"}');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $token = $google->getAccessToken('test-auth-code');

        $this->assertEquals('test-access-token', $token);
    }

    public function testGetAccessTokenFailRequest()
    {
        $this->expectException(AccessTokenNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');
        $settings->get('redirect_uri')->willReturn('test-redirect-uri');

        $response = Response::badRequest();
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $token = $google->getAccessToken('test-auth-code');
    }

    public function testGetAccessTokenBrokenResponse()
    {
        $this->expectException(AccessTokenNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $settings->get('client_id')->willReturn('test-client-id');
        $settings->get('client_secret')->willReturn('test-client-secret');
        $settings->get('redirect_uri')->willReturn('test-redirect-uri');

        $response = Response::ok()->withBody('{"access_token":"test-');
        $http_client = $this->prophesize(IHttpClient::class);
        $http_client->send(Argument::that([$this, 'prpCheckTokenRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $token = $google->getAccessToken('test-auth-code');
    }

    public function testGetUserInfo()
    {
        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::ok()->withBody('{"id":123,"name":"FooBar"}');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $user_info = $google->getUserInfo('test-access-token');

        $this->assertInstanceOf(UserInfo::class, $user_info);
        $this->assertEquals(123, $user_info->identifier());
        $this->assertEquals('FooBar', $user_info->name());
        $this->assertEquals('', $user_info->email());
    }

    public function testGetUserInfoFailResponse()
    {
        $this->expectException(UserInfoNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::badRequest();
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $user_info = $google->getUserInfo('test-access-token');
    }

    public function testGetUserInfoBrokenResponse()
    {
        $this->expectException(UserInfoNotTaken::class);

        $settings = $this->prophesize(ISettings::class);
        $http_client = $this->prophesize(IHttpClient::class);

        $response = Response::ok()->withBody('{"id":123,"name":"FooB');
        $http_client->send(Argument::that([$this, 'prpCheckUserInfoRequest']))
                    ->willReturn($response);

        $google = new ProviderGoogle($settings->reveal(), $http_client->reveal());
        $user_info = $google->getUserInfo('test-access-token');
    }

    public function prpCheckTokenRequest(IRequest $request)
    {
        $eurl = 'https://www.googleapis.com/oauth2/v4/token';
        $ebody = 'client_id=test-client-id&' .
                 'client_secret=test-client-secret&' .
                 'code=test-auth-code&' .
                 'grant_type=authorization_code&' .
                 'redirect_uri=test-redirect-uri';

        if ($request->getUri()->__toString() != $eurl) {
            return false;
        };

        if ($request->getMethod() != IRequest::METHOD_POST) {
            return false;
        };

        if ($request->getBody() != $ebody) {
            return false;
        };

        return true;
    }

    public function prpCheckUserInfoRequest(IRequest $request)
    {
        $eurl = 'https://www.googleapis.com/userinfo/v2/me';

        if ($request->getUri()->__toString() != $eurl) {
            return false;
        };

        if ($request->getMethod() != IRequest::METHOD_GET) {
            return false;
        };

        if (!$request->hasHeader('Authorization')) {
            return false;
        };

        if ($request->getHeader('Authorization') != 'Bearer test-access-token') {
            return false;
        };

        return true;
    }
}
