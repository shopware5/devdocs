<?php

namespace SwagEvents\Components;

class NameClass3 implements NameClassInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::class;
    }
}