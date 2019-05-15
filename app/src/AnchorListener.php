<?php
namespace Shopware\Devdocs;

use IvoPetkov\HTML5DOMDocument;
use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AnchorListener implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    private $usedAnchors = [];

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            Sculpin::EVENT_AFTER_FORMAT => 'afterFormat',
        ];
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
        $parts = explode('.', $source->filename());

        if (count($parts) < 2) {
            return;
        }

        if (!in_array($parts[1], ['md', 'html'], true)) {
            return;
        }

        $content = $source->formattedContent();

        if (empty($content)) {
            return;
        }

        $dom = new HTML5DOMDocument();
        $dom->loadHTML($content);
        if (!$dom) {
            return;
        }

        // Reset stateful anchor property
        $this->usedAnchors = [];

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

            $counter = 0;
            $tmpId = $id;
            while (array_key_exists($tmpId, $this->usedAnchors)) {
                $tmpId = $id.'-'.$counter++;
            }
            $id = $tmpId;

            $element->setAttribute('id', $id);
            $this->usedAnchors[$id] = true;
        }
    }
}
