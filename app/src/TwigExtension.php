<?php

namespace Shopware\Devdocs;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $environment;

    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('versioned', [$this, 'versioned']),
        ];
    }

    /**
     * @param string $resource
     * @return string
     */
    public function versioned($resource)
    {
        $parts = pathinfo($resource);
        $version = ($this->environment === 'prod') ? '.' . time() : '';

        return $parts['dirname']
            . '/'
            . $parts['filename']
            . $version
            . '.'
            . $parts['extension'];
    }
}
