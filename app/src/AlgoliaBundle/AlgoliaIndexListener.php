<?php
namespace Shopware\Devdocs\AlgoliaBundle;

use AlgoliaSearch\Client;
use AlgoliaSearch\Index;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Source\AbstractSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AlgoliaIndexListener implements EventSubscriberInterface
{
    /**
     * @var Index
     */
    private $index;

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_AFTER_RUN => 'afterRun',
        );
    }

    /**
     * @param Client $client
     * @param string $indexName
     */
    public function __construct(Client $client, $indexName)
    {
        $this->index = $client->initIndex($indexName);
        $this->index->setSettings(array(
            "attributesToIndex" => array("title", "tags", "unordered(body)"),
            'attributesForFaceting' => array('tags')
        ));
    }

    /**
     * @param \Sculpin\Core\Event\SourceSetEvent $event
     */
    public function afterRun(SourceSetEvent $event)
    {
        $documents = array();
        /** @var AbstractSource $item */
        foreach ($event->allSources() as $item) {
            if ($item->data()->get('indexed')) {
                if ($item->isGenerated()) {
                    continue;
                }

                $documents[] = $this->parseSource($item);
            }
        }

        $this->index->clearIndex();
        $this->index->addObjects($documents);
    }

    /**
     * @param AbstractSource $source
     * @return array
     */
    private function parseSource(AbstractSource $source)
    {
        $document = array(
            'objectID' => sha1($source->sourceId()),
            'title' => $source->data()->get('title'),
            'body'  => strip_tags($source->content()),
            'url'   => rtrim($source->permalink()->relativeUrlPath(), '/').'/',
            'date' => $source->data()->get('calculated_date'),
        );

        $tags = (is_array($source->data()->get('tags'))) ? $source->data()->get('tags') : array();
        if ($tags) {
            $document['tags'] = $tags;
        }

        return $document;
    }
}
