<?php

namespace SwagEvents\Components;

class NameClass4 implements NameClassInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::class;
    }
}