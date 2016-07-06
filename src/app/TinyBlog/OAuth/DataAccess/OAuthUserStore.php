<?php

namespace TinyBlog\OAuth\DataAccess;

use Yada\Driver as SqlDriver;
use Yada\Statement as SqlStatement;
use TinyBlog\OAuth\OAuthUser;
use TinyBlog\User\User;

class OAuthUserStore
{
    private $sql_driver;

    public function __construct(SqlDriver $sql_driver)
    {
        $this->sql_driver = $sql_driver;
    }

    public function insert(OAuthUser $oauser)
    {
        $sql = 'insert into `oauth_user` (`user_id`, `provider`, `identifier`)
                values
                (:user_id, :provider, :identifier)';

        $this->sql_driver
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

        $stmt = $this->sql_driver->prepare($sql);
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

        return $this->makeResult($stmt->execute());
    }

    private function makeWhere(array $cond)
    {
        if (!count($cond)) {
            return 1;
        };

        $where = [];
        $keys = array_intersect(['user_id', 'provider', 'identifier'], array_keys($cond));
        foreach ($keys as $key) {
            $where[] = sprintf('`%s` = :%s', $key, $key);
        };

        return implode(' and ', $where);
    }

    private function makeResult(SqlStatement $stmt)
    {
        $result = [];

        while ($row = $stmt->fetch()) {
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
