<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace SwagEmotionPresetExample;

use Shopware\Components\Emotion\Preset\PresetInstaller;
use Shopware\Components\Emotion\Preset\PresetMetaDataInterface;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use SwagEmotionPresetExample\Components\Emotion\Presets\MyCustomPreset;

class SwagEmotionPresetExample extends Plugin
{
    public static function getAssetPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'Resources/assets';
    }

    public function install(InstallContext $context)
    {
        /** @var PresetInstaller $presetInstallerService */
        $presetInstallerService = $this->container->get('shopware.emotion.preset_installer');

        $presetInstallerService->installOrUpdate($this->getPresetInstances());
    }

    public function uninstall(UninstallContext $context)
    {
        /** @var PresetInstaller $presetInstallerService */
        $presetInstallerService = $this->container->get('shopware.emotion.preset_installer');

        $presetInstallerService->uninstall([
            'my_custom_preset',
        ]);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getJsonData($fileName)
    {
        $json = '{}';
        $jsonPath = SwagEmotionPresetExample::getAssetPath() . '/presetData/' . trim($fileName, '/');
        if (file_exists($jsonPath)) {
            $json = file_get_contents($jsonPath);
        }
        $json = str_replace('___ASSETPATH___', SwagEmotionPresetExample::getAssetPath(), $json);
        return $json;
    }

    /**
     * @return PresetMetaDataInterface[]
     */
    private function getPresetInstances()
    {
        return [
            new MyCustomPreset()
        ];
    }
}
