<?php

namespace SwagCustomSlugService;

use Shopware\Components\Slug\SlugInterface;

class SlugService implements SlugInterface
{
    /**
     * @var SlugInterface
     */
    private $coreSlugService;

    /**
     * @param SlugInterface $core
     */
    public function __construct(SlugInterface $core)
    {
        $this->coreSlugService = $core;
    }

    /**
     * Return a URL safe version of a string.
     *
     * @param string $string
     * @param string|null $separator
     *
     * @return string
     */
    public function slugify($string, $separator = null)
    {
        $string = html_entity_decode($string);

        return $this->coreSlugService->slugify($string, $separator);
    }
}