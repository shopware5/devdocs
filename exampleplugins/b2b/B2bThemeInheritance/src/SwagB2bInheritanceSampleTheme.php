<?php declare(strict_types=1);

namespace SwagB2bInheritanceSampleTheme;

use Shopware\Core\Framework\Plugin;
use Shopware\Storefront\Framework\ThemeInterface;

class SwagB2bInheritanceSampleTheme extends Plugin implements ThemeInterface
{
    public function getThemeConfigPath(): string
    {
        return 'theme.json';
    }
}