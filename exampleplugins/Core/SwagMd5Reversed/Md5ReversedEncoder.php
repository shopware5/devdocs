<?php

namespace Shopware\SwagMd5Reversed;

use Shopware\Components\Password\Encoder\PasswordEncoderInterface;

class Md5ReversedEncoder implements PasswordEncoderInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Md5Reversed';
    }

    /**
     * This is just an example. MD5 is not recommended for password encryption!
     *
     * @param  string $password
     * @return string
     */
    public function encodePassword($password)
    {
        return md5(strrev($password));
    }

    /**
     * @param  string $password
     * @param  string $hash
     * @return bool
     */
    public function isPasswordValid($password, $hash)
    {
        return $this->encodePassword($password) === $hash;
    }

    /**
     * Only used, for e.g. bcrypt which keeps track of the "rounds" the password was encoded
     *
     * @param  string $hash
     * @return bool
     */
    public function isReencodeNeeded($hash)
    {
        return false;
    }

}