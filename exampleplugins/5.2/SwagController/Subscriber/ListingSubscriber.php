<?php

namespace SwagController\Subscriber;

use Enlight\Event\SubscriberInterface;

class ListingSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onListingIndex'
        ];
    }

    /**
     * Event callback for the event registered above
     */
    public function onListingIndex(\Enlight_Event_EventArgs $args)
    {
        /** @var $controller \Enlight_Controller_Action */
        $controller = $args->getSubject();
        $request = $controller->Request();

        if($request->getActionName() === 'index') {
            $view = $controller->View();
            $response = $controller->Response();
            $parameter = $request->getParams();

            // DO SOMETHING TO EXTEND OR MODIFY

            echo '<pre>';
            echo 'TODO: Extend or modify listing';
            echo '<br />';
            echo '<br />';
            var_export($parameter);
            die();
        }
    }
}