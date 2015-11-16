<?php

namespace Shopware\SwagDynamicEmotion\Subscriber;

use Shopware\Components\Model\ModelRepository;
use Shopware\SwagDynamicEmotion\Components\CustomComponents;

class Emotion implements \Enlight\Event\SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            'Shopware_Controllers_Widgets_Emotion_AddElement' => 'handleElement',
            'Enlight_Controller_Action_PostDispatchSecure_Backend_Emotion' => 'modifyEmotionModule',
            'Shopware\Models\Emotion\Repository::getListingQuery::after' => 'removeShopTemplatEmotionFromListing'
        );
    }

    /**
     * Will hide our shopping world from the emotion module.
     *
     * @param \Enlight_Hook_HookArgs $args
     * @return mixed
     */
    public function removeShopTemplatEmotionFromListing(\Enlight_Hook_HookArgs $args)
    {
        $builder = $args->getReturn();

        $builder->leftJoin('emotions', 's_emotion_attributes', 'attribute', 'attribute.emotionID = emotions.id')
            ->andWhere('attribute.swag_shop_template IS NULL or attribute.swag_shop_template != 1');

        return $builder;

    }

    /**
     * Event callback to make the emotion module subapplication aware and remove our custom components from the library
     * in the *default* emotion module
     *
     * @param \Enlight_Event_EventArgs $args
     */
    public function modifyEmotionModule(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->get('subject');
        $request = $controller->Request();
        $view = $controller->View();

        // subapplication awareness
        if ($request->getActionName() == 'load') {
            $view->extendsTemplate('backend/swag_emotion/controller/main.js');
        }

        // remove our components from the default emotion library
        // our compoments should just be visible when editing our store emotion template
        if ($request->getActionName() == 'library' && !$request->has('showStoreComponents')) {
            /** @var CustomComponents $customComponents */
            $customComponents = $controller->get('swag_dynamic_emotion.custom_components');

            $data = $view->getAssign('data');
            foreach ($data as $key => $component) {
                // remove the custom elements from the default emotion module
                if ($customComponents->isCustomComponents($component['cls'])) {
                    unset($data[$key]);
                }
            }
            $view->assign('data', $data);
        }

    }

    /**
     * Provide the store data for the current store.
     *
     * @param \Enlight_Event_EventArgs $args
     * @return array|mixed
     */
    public function handleElement(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->get('subject');
        /** @var CustomComponents $customComponents */
        $customComponents = $controller->get('swag_dynamic_emotion.custom_components');

        $element = $args->get('element');
        $data = $args->getReturn();
        $storeId = $controller->Request()->getParam('currentStore');

        // just modify our own components
        if (!$customComponents->isCustomComponents($element['component']['cls'])) {
            return $data;
        }

        // if no $storeId is available (e.g. shopping world preview), get a fallback
        $storeId = isset($storeId) ? $storeId : Shopware()->Db()->fetchOne('SELECT id FROM swag_store LIMIT 1');

        // if still not available (e.g. no stores) - return
        if (!$storeId) {
            return $data;
        }

        /** @var ModelRepository $storeRepo */
        $storeRepo = Shopware()->Models()->getRepository('Shopware\CustomModels\SwagDynamicEmotion\Store');
        return array_merge($data, ['store' => $storeRepo->find($storeId)]);
    }

}