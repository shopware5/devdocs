<?php

namespace SwagEvents\Components;

class NameClass1 implements NameClassInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::class;
    }
}