<?php
namespace Shopware\Devdocs;

use Sculpin\Core\Sculpin;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Source\AbstractSource;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchIndexListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $outputDir;

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Sculpin::EVENT_AFTER_RUN => 'afterRun',
        ];
    }

    /**
     * @param string $outputDir
     */
    public function __construct($outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * @param SourceSetEvent $event
     */
    public function afterRun(SourceSetEvent $event)
    {
        $documents = [];
        /** @var AbstractSource $item */
        foreach ($event->allSources() as $item) {
            if ($item->data()->get('indexed')) {
                if ($item->isGenerated()) {
                    continue;
                }

                $documents[] = $this->parseSource($item);
            }
        }

        $output['entries'] = $documents;
        $json = json_encode($output, JSON_PRETTY_PRINT);

        file_put_contents($this->outputDir.'/index.json', $json);
    }

    /**
     * @param AbstractSource $source
     * @return array
     */
    private function parseSource(AbstractSource $source)
    {
        $tags = is_array($source->data()->get('tags')) ? $source->data()->get('tags') : [];

        $document = [
            'title' => $source->data()->get('title'),
            'body'  => strip_tags($source->content()),
            'tags'  => implode(', ', $tags),
            'url'   => rtrim($source->permalink()->relativeUrlPath(), '/').'/',
        ];

        return $document;
    }
}
