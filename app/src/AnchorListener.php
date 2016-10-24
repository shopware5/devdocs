<?php
namespace Shopware\Devdocs;

use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnchorListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_AFTER_FORMAT => 'afterFormat',
        );
    }

    public function afterFormat(SourceSetEvent $event)
    {
        foreach ($event->allSources() as $source) {
            $this->formatSource($source);
        }
    }

    private function formatSource(SourceInterface $source)
    {
        if (!$source->hasChanged()) {
            return;
        }

        if ($source->isGenerated()) {
            return;
        }

        if (!$source->canBeFormatted()) {
            return;
        }

        if ($source->isRaw()) {
            return;
        }

        $content = $source->formattedContent();

        if (empty($content)) {
            return;
        }

        $dom = new \IvoPetkov\HTML5DOMDocument();
        $dom->loadHTML($content);
        if (!$dom) {
            return;
        }

        foreach (['h2', 'h3', 'h4'] as $tagName) {
            /** @var \DOMNodeList $elements */
            $elements = $dom->getElementsByTagName($tagName);
            $this->addAnchors($elements);
        }

        $output = $dom->saveHTML();
        $source->setFormattedContent($output);
    }

    /**
     * @param $elements
     */
    private function addAnchors($elements)
    {
        /** @var \DOMElement $element */
        foreach ($elements as $element) {
            $id = $element->nodeValue;
            $id = strtolower($id);
            $id = preg_replace('/\s+/', '-', $id);
            $id = preg_replace('/-+/', '-', $id);
            $element->setAttribute('id', $id);
        }
    }
}
