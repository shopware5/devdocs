<?php

namespace Shopware\Devdocs;

use Sculpin\Bundle\ThemeBundle\ThemeTwigExtension;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @var ThemeTwigExtension
     */
    private $twigThemeExtension;

    public function __construct(ThemeTwigExtension $twigThemeExtension, $environment)
    {
        $this->twigThemeExtension = $twigThemeExtension;
        $this->environment        = $environment;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return __CLASS__;
    }

    public function getFunctions()
    {
        return array(
            'versioned' => new \Twig_Function_Method($this, 'versioned'),
        );
    }

    /**
     * @param string $resource
     * @return string
     */
    public function versioned($resource)
    {
        $parts  = pathinfo($resource);
        $version = ($this->environment === 'prod') ? '.'.time() : '';

        return $parts['dirname']
        .'/'
        .$parts['filename']
        .$version
        .'.'
        .$parts['extension'];
    }
}
