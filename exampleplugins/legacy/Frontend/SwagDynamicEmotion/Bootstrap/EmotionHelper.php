<?php

namespace Shopware\SwagDynamicEmotion\Bootstrap;

class EmotionHelper
{
    /**
     * @var \Shopware_Plugins_Frontend_SwagDynamicEmotion_Bootstrap
     */
    private $bootstrap;

    public function __construct(\Shopware_Plugins_Frontend_SwagDynamicEmotion_Bootstrap $bootstrap)
    {
        $this->bootstrap = $bootstrap;
    }

    public function create()
    {
        list($openInfoComponent, $mapComponent, $descriptionComponent) = $this->createMyEmotionComponent();
        $this->createStoreShoppingWorld($openInfoComponent, $mapComponent, $descriptionComponent);
    }

    private function createMyEmotionComponent()
    {
        $descriptionComponent = $this->bootstrap->createEmotionComponent(
            array(
                'name' => 'Description',
                'template' => 'component_description',
                'cls' => 'dynamic_emotion_description',
                'description' => 'Shop description - will show the shop\'s description'
            )
        );
        $openInfoComponent = $this->bootstrap->createEmotionComponent(
            array(
                'name' => 'Opening times',
                'template' => 'component_opening',
                'cls' => 'dynamic_emotion_opening_times',
                'description' => 'Shop opening times'
            )
        );
        $mapComponent = $this->bootstrap->createEmotionComponent(
            [
                'name' => 'Map',
                'template' => 'component_map',
                'cls' => 'dynamic_emotion_map',
                'description' => 'Shop a google map link'
            ]
        );
        $mapComponent->createNumberField(
            [
                'name' => 'zoom',
                'defaultValue' => '17',
                'minValue' => 1,
                'maxValue' => 21,
                'position' => 1
            ]
        );


        return [$openInfoComponent, $mapComponent, $descriptionComponent];
    }


    private function createStoreShoppingWorld($openInfoComponent, $mapComponent, $descriptionComponent)
    {
        $em = Shopware()->Models();
        $attributeEmotionRepo = $em->getRepository('Shopware\Models\Attribute\Emotion');
        $templateRepo = $em->getRepository('Shopware\Models\Emotion\Template');
        $grideRepo = $em->getRepository('Shopware\Models\Emotion\Grid');

        // Do not create multiple times
        $attribute = $attributeEmotionRepo->findOneBy(['swagShopTemplate' => 1]);
        if ($attribute && $attribute->getEmotion()) {
            $em->remove($attribute->getEmotion());
            $em->flush();
        }

        // Emotion
        $emotion = new \Shopware\Models\Emotion\Emotion();

        // description element:
        $descriptionElement = new \Shopware\Models\Emotion\Element();
        $descriptionElement->setComponent($descriptionComponent);
        $descriptionElement->setEmotion($emotion);
        $descriptionElement->setStartCol(1);
        $descriptionElement->setEndCol(3);
        $descriptionElement->setStartRow(1);
        $descriptionElement->setEndRow(1);

        // map
        $mapElement = new \Shopware\Models\Emotion\Element();
        $mapElement->setComponent($mapComponent);
        $mapElement->setEmotion($emotion);
        $mapElement->setStartCol(1);
        $mapElement->setEndCol(3);
        $mapElement->setStartRow(2);
        $mapElement->setEndRow(2);

        // open info
        $openInfoElement = new \Shopware\Models\Emotion\Element();
        $openInfoElement->setComponent($openInfoComponent);
        $openInfoElement->setEmotion($emotion);
        $openInfoElement->setStartCol(4);
        $openInfoElement->setEndCol(4);
        $openInfoElement->setStartRow(1);
        $openInfoElement->setEndRow(2);

        $emotion->fromArray(
            [
                'element' => [$openInfoElement, $descriptionElement, $mapElement],
                'active' => 1,
                'name' => 'Shop base layout',
                'isLandingPage' => true,
                'containerWidth' => 1080,
                'rows' => 20,
                'grid' => $grideRepo->findOneBy(['id' => 2]),
                'template' => $templateRepo->findOneBy(['id' => 1]),
                'device' => '0,1,2,3,4',
                'mode' => 'masonry',
                'position' => 1,
                'fullscreen' => false,
                'landingPageBlock' => '',
                'landingPageTeaser' => '',
                'seoKeywords' => '',
                'seoDescription' => '',
                'showListing' => false,
            ]
        );

        // Attribute
        $attribute = new \Shopware\Models\Attribute\Emotion();
        $attribute->setEmotion($emotion);
        $attribute->setSwagShopTemplate(true);

        $em->persist($emotion);
        $em->persist($attribute);
        $em->persist($descriptionElement);
        $em->persist($mapElement);
        $em->persist($openInfoElement);
        $em->persist($descriptionComponent);
        $em->persist($mapComponent);
        $em->persist($openInfoComponent);

        $em->flush($emotion);
    }
}
