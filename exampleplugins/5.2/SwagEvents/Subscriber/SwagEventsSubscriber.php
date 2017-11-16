<?php

namespace SwagEvents\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use SwagEvents\Components\NameClass3;
use SwagEvents\Components\NameClass4;

class SwagEventsSubscriber implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'SwagEvent_Controller_notifyAction' => 'onNotify',
            'SwagEvent_Controller_notifyUntilAction' => 'onNotifyUntil',
            'SwagEvent_Controller_filterAction' => 'onFilter',
            'SwagEvent_Controller_collectAction' => 'onCollect',
        ];
    }


    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onNotify(\Enlight_Event_EventArgs $args)
    {
        echo '<pre>';
        echo 'Do some magic';
        echo '<br />';
        \Doctrine\Common\Util\Debug::dump($args);
        echo '<br />';
        var_export($args->get('payload'));
        echo '<br />';
        var_export($args->get('payload2'));
        echo '<br />';

        die('END');
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     *
     * @return bool | Void
     */
    public function onNotifyUntil(\Enlight_Event_EventArgs $args)
    {
        $stop = $args->get('stop');

        if ($stop) {
            // if you return some result you stop the callStack
            return true;
        }
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     *
     * @return array
     */
    public function onFilter(\Enlight_Event_EventArgs $args)
    {
        $return = $args->get('data');
        $value = $args->getReturn();

        if ($value === 'some value') {
            foreach ($return as $key => $value) {
                if ($value['id'] === 2) {
                    $return[$key] = [
                        'id' => 178
                    ];
                }
            }
        }

        return $return;
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     *
     * @return ArrayCollection
     */
    public function onCollect(\Enlight_Event_EventArgs $args)
    {
        return new ArrayCollection(
            [
                new NameClass3(),
                new NameClass4()
            ]
        );
    }
}