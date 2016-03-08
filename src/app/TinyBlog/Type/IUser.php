<?php

namespace TinyBlog\Type;

interface IUser
{
    public function getId();
    public function getNickname();
    public function getUsername();
    public function getPassword();

    public function withId($id);
    public function withNickname($nickname);
    public function withUsername($username);
    public function withPassword($password);
}
