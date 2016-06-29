<?php

namespace TinyBlogTest\Web\Service;

use Yen\Session\Contract\ISession;
use Yen\Session\SessionStorage;
use TinyBlog\User\UserRepo;
use TinyBlog\User\User;
use TinyBlog\Web\Service\UserAuthenticator;

class UserAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAuthenticateHappyPath()
    {
        $session = $this->prophesize(ISession::class);

        $repo = $this->prophesize(UserRepo::class);
        $repo->usernameExists('foobar')
             ->willReturn(true);
        $ret_user = new User([
            'username' => 'foobar',
            'password' => '$2y$10$7s.TyykpC41TIRFUpSjg.eaMrZtOT8MrseJEt2hENRhDIb7z7aob2'
        ]);
        $repo->getByUsername('foobar')
             ->willReturn($ret_user);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $result = $authenticator->authenticate('foobar', 'password');

        $this->assertTrue($result);
    }

    public function testAuthenticateInvalidUsername()
    {
        $session = $this->prophesize(ISession::class);

        $repo = $this->prophesize(UserRepo::class);
        $repo->usernameExists('foobar')
             ->willReturn(false);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $result = $authenticator->authenticate('foobar', 'password');

        $this->assertFalse($result);
    }

    public function testAuthenticateInvalidPassword()
    {
        $session = $this->prophesize(ISession::class);

        $repo = $this->prophesize(UserRepo::class);
        $repo->usernameExists('foobar')
             ->willReturn(true);
        $ret_user = new User([
            'username' => 'foobar',
            'password' => '$2y$10$7s.TyykpC41TIRFUpSjg.eaMrZtOT8MrseJEt2hENRhDIb7z7aob2'
        ]);
        $repo->getByUsername('foobar')
             ->willReturn($ret_user);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $result = $authenticator->authenticate('foobar', 'secret');

        $this->assertFalse($result);
    }

    public function testSetAuthUser()
    {
        $session = $this->prophesize(ISession::class);
        $storage = new SessionStorage('auth');
        $session->getStorage('auth')
                ->willReturn($storage);

        $repo = $this->prophesize(UserRepo::class);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $user = new User(['id' => 123]);
        $result = $authenticator->setAuthUser($user);

        $this->assertSame($authenticator, $result);
        $this->assertEquals(123, $storage->get('user_id'));
    }

    public function testGetAuthUser()
    {
        $session = $this->prophesize(ISession::class);
        $storage = new SessionStorage('auth');
        $storage->set('user_id', 123);
        $session->getStorage('auth')
                ->willReturn($storage);

        $repo = $this->prophesize(UserRepo::class);
        $repo->userExists(123)
             ->willReturn(true);
        $ret_user = new User([
            'id' => 123,
            'username' => 'foobar'
        ]);
        $repo->getById(123)
             ->willReturn($ret_user);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $result = $authenticator->getAuthUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(123, $result->getId());
        $this->assertEquals('foobar', $result->getUsername());
    }

    public function testGetAuthUserNone()
    {
        $session = $this->prophesize(ISession::class);
        $storage = new SessionStorage('auth');
        $session->getStorage('auth')
                ->willReturn($storage);

        $repo = $this->prophesize(UserRepo::class);
        $repo->userExists(0)
             ->willReturn(false);

        $authenticator = new UserAuthenticator($session->reveal(), $repo->reveal());
        $result = $authenticator->getAuthUser();

        $this->assertInstanceOf(User::class, $result);
        $this->assertNull($result->getId());
        $this->assertEquals('', $result->getUsername());
        $this->assertTrue($result->isGuest());
    }
}
