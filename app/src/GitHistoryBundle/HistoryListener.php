<?php
namespace Shopware\Devdocs\GitHistoryBundle;

use Sculpin\Core\Event\SourceSetEvent;
use Sculpin\Core\Permalink\SourcePermalinkFactoryInterface;
use Sculpin\Core\Sculpin;
use Sculpin\Core\Source\SourceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Process\Process;

class HistoryListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var SourcePermalinkFactoryInterface
     */
    private $permalinkFactory;

    /**
     * @var array
     */
    private static $blacklist = [
        'source/index.html'
    ];

    /**
     * @param string $projectDir
     * @param SourcePermalinkFactoryInterface $permalinkFactory
     */
    public function __construct($projectDir, SourcePermalinkFactoryInterface $permalinkFactory)
    {
        $this->projectDir = $projectDir;
        $this->permalinkFactory = $permalinkFactory;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_BEFORE_RUN => 'dumpGitHistory',
        );
    }

    /**
     * @param SourceSetEvent $event
     */
    public function dumpGitHistory(SourceSetEvent $event)
    {
        /** @var SourceInterface $source */
        foreach ($event->allSources() as $source) {
            if (preg_match('#/source/index.html$#i', $source->file()->getPathname())) {
                if ($source->data()->get('docHistory')) {
                    return;
                }

                $this->addHistoryToSource($source, $event->allSources());
            }
        }
    }

    /**
     * @param SourceInterface $source
     * @param array $sources
     */
    private function addHistoryToSource(SourceInterface $source, array $sources)
    {
        $data = $this->fetchGitHistory();
        $history = [];

        foreach ($data as $key => $item) {
            foreach ($item['articles'] as $articleKey => $article) {
                $resource = $this->findArticle($sources, $article);

                if (!$resource) {
                    continue;
                }

                $permalink = $this->permalinkFactory->create($resource);
                $url = rtrim($permalink->relativeUrlPath(), '/') . '/';

                $title = $resource->data()->get('title');

                $history[$item['date']][$title] = $url;
            }
        }

        foreach ($history as $date => $items) {
            ksort($items);
            $history[$date] = $items;
        }

        $source->data()->set('docHistory', $history);
    }

    /**
     * @param array $sources
     * @param string $article
     *
     * @return null|SourceInterface
     */
    private function findArticle(array $sources, $article)
    {
        /** @var SourceInterface $source */
        foreach ($sources as $source) {
            if (preg_match('#' . $article . '$#i', $source->file()->getPathname())) {
                return $source;
            }
        }

        return null;
    }

    /**
     * @param int $numOfCommits
     *
     * @return array
     */
    private function fetchGitHistory($numOfCommits = 50)
    {
        $process = new Process(sprintf("git log --oneline --merges -%d | cut -d' ' -f1", $numOfCommits), $this->projectDir);
        $process->run();

        $commits = array_filter(explode(PHP_EOL, $process->getOutput()));

        if (empty($commits)) {
            return [];
        }

        $history = [];
        $latestHash = 'HEAD^';

        foreach ($commits as $commit) {
            $getFilesProcess = new Process(sprintf('git diff --name-only %s %s', $commit, $latestHash), $this->projectDir);
            $getFilesProcess->run();

            $changedFiles = array_filter(
                explode(PHP_EOL, $getFilesProcess->getOutput()),
                function ($file) {
                    if (preg_match('#source/.*\.(md|html)#i', $file) && !in_array($file, self::$blacklist, true)) {
                        return true;
                    }
                }
            );

            if (empty($changedFiles)) {
                continue;
            }

            $commitDateProcess = new Process(sprintf('git show --pretty=%%ct %s', $commit), $this->projectDir);
            $commitDateProcess->run();

            $commitDate = trim($commitDateProcess->getOutput());

            $latestHash = $commit;

            $history[] = [
                'date' => date('Y-m-d', $commitDate),
                'articles' => $changedFiles
            ];
        }

        return $history;
    }
}
