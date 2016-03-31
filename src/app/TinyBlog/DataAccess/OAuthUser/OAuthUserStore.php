<?php

namespace TinyBlog\DataAccess\OAuthUser;

use Yada\Driver;
use TinyBlog\Type\OAuthUser;
use TinyBlog\Type\User;

class OAuthUserStore
{
    protected $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function insert(OAuthUser $oauser)
    {
        $sql = 'insert into `oauth_user` (`user_id`, `provider`, `identifier`)
                values
                (:user_id, :provider, :identifier)';

        $this->driver
             ->prepare($sql)
             ->bindInt(':user_id', $oauser->getUser()->getId())
             ->bindInt(':provider', $oauser->getProvider())
             ->bindString(':identifier', $oauser->getIdentifier())
             ->execute();

        return $this;
    }

    public function fetch(array $cond = [])
    {
        $sql = sprintf(
            'select oau.*, u.nickname
             from `oauth_user` as oau
             inner join `user` as u on u.id = oau.user_id
             where %s',
            $this->makeWhere($cond)
        );

        $stmt = $this->driver->prepare($sql);
        foreach ($cond as $key => $value) {
            switch ($key) {
                case 'user_id':
                case 'provider':
                    $stmt->bindInt(':' . $key, $value);
                    break;
                case 'identifier':
                    $stmt->bindString(':' . $key, $value);
                    break;
                default:
                    break;
            };
        };

        return $this->makeResult($stmt->execute()->fetchAll());
    }

    protected function makeWhere(array $cond)
    {
        if (!count($cond)) {
            return 1;
        };

        $where = [];
        foreach (array_keys($cond) as $key) {
            $where[] = sprintf('`%s` = :%s', $key, $key);
        };

        return implode(' and ', $where);
    }

    protected function makeResult(array $rows)
    {
        $result = [];
        foreach ($rows as $row) {
            $user = new User([
                'id' => $row['user_id'],
                'nickname' => $row['nickname']
            ]);

            $oauser = new OAuthUser([
                'user' => $user,
                'provider' => $row['provider'],
                'identifier' => $row['identifier']
            ]);

            $result[] = $oauser;
        };

        return $result;
    }
}
