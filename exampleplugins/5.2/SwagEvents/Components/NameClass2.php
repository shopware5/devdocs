<?php

namespace SwagEvents\Components;

class NameClass2 implements NameClassInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::class;
    }
}