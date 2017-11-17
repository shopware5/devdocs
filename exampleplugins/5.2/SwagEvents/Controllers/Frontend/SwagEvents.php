<?php

use Doctrine\Common\Collections\ArrayCollection;
use SwagEvents\Components\NameClassInterface;

class Shopware_Controllers_Frontend_SwagEvents extends Enlight_Controller_Action
{
    public function preDispatch()
    {
        // set no renderer is only for testing without creating a template.
        $this->container->get('front')->Plugins()->ViewRenderer()->setNoRender();
    }
    
    public function notifyAction()
    {
        // do some magic

        $this->container->get('events')->notify(
            'SwagEvent_Controller_notifyAction', // give the event a unique name and add the payload
            [
                'payload' => 123,
                'payload2' => 'more Payload'
            ]
        );

        // do some magic
    }

    public function notifyUntilAction()
    {
        // do some magic

        $stop = $this->container->get('events')->notifyUntil(
            'SwagEvent_Controller_notifyUntilAction',
            [
                // Edit the stop boolean and see the different behavior.
                'stop' => false
            ]
        );

        if ($stop) {
            echo '<pre>';
            var_export('Stop is true');
            echo '<br />';
            return;
        }

        echo '<pre>';
        var_export('Stop is false');
        echo '<br />';
        die('End');
    }

    public function filterAction()
    {
        $result = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        $eventManager = $this->container->get('events');

        $result = $eventManager->filter('SwagEvent_Controller_filterAction', 'some value', ['data' => $result]);

        echo '<pre>';
        var_export($result);
        echo '<br />';
        die('END');
    }

    public function collectAction()
    {
        $collection = new ArrayCollection([
            new \SwagEvents\Components\NameClass1(),
            new \SwagEvents\Components\NameClass2()
        ]);

        $eventManager = $this->container->get('events');
        $eventManager->collect('SwagEvent_Controller_collectAction', $collection);

        /** @var NameClassInterface $nameClass */
        foreach ($collection->toArray() as $nameClass) {
            echo $nameClass->getName();
            echo '<br />';
        }

        echo '<pre>';
        \Doctrine\Common\Util\Debug::dump($collection->toArray());
        echo '<br />';
        die('END');
    }
}