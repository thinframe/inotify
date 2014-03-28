<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Inotify;

use Symfony\Component\Finder\Finder;
use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
use ThinFrame\Events\DispatcherAwareTrait;
use ThinFrame\Foundation\Constant\DataType;
use ThinFrame\Foundation\Helper\TypeCheck;

/**
 * FileSystemWatcher
 *
 * @package ThinFrame\Inotify
 * @since   0.2
 */
class FileSystemWatcher implements DispatcherAwareInterface
{
    use DispatcherAwareTrait;

    /**
     * iNotify resource
     *
     * @var null|resource
     */
    private $resource = null;
    /**
     * @var array
     */
    private $watchDescriptors = [];

    /**
     * @var Finder
     */
    private $finder;

    /**
     * Default iNotify mode
     * @var int
     */
    private $defaultMode;

    /**
     * Constructor
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->defaultMode = IN_MODIFY | IN_DELETE | IN_CREATE;

        $this->finder = $finder;

        $this->resource = inotify_init();
        stream_set_blocking($this->resource, 0);
        register_tick_function([$this, 'tick']);

        $this->finder
            ->ignoreDotFiles(true)
            ->ignoreUnreadableDirs(true)
            ->ignoreVCS(true)
            ->followLinks();

    }

    /**
     * Attach the dispatcher
     *
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        if ($this->dispatcher) {
            return;
        }
        $this->dispatcher = $dispatcher;
        $this->dispatcher->attachTo(InotifyEvent::EVENT_ID, [$this, 'handleDirectories']);
    }


    /**
     * Tick handler
     */
    public function tick()
    {
        $events = inotify_read($this->resource);
        if (is_array($events)) {
            $this->handleEvents($events);
        }
    }

    /**
     * Check if changed item is a directory and add/remove it from file system watcher
     *
     * @param InotifyEvent $event
     */
    public function handleDirectories(InotifyEvent $event)
    {
        $path = $event->getPayload()->get('path')->get();
        $data = $event->getPayload()->get('inotify')->get();

        clearstatcache(true, $path . DIRECTORY_SEPARATOR . $data['name']);
        if (is_dir($path . DIRECTORY_SEPARATOR . $data['name'])) {
            $this->watchPath($path . DIRECTORY_SEPARATOR . $data['name'], $this->defaultMode);
        }
    }

    /**
     * Handle received events
     *
     * @param array $events
     */
    private function handleEvents(array $events)
    {
        foreach ($events as $event) {
            $watchDescriptor = intval($event['wd']);
            $this->dispatcher->trigger(
                new InotifyEvent(['path' => $this->watchDescriptors[$watchDescriptor], 'inotify' => $event])
            );
        }
    }

    /**
     * Watch a path
     *
     * @param string $path
     * @param int    $mode
     * @param bool   $recursive
     */
    public function watchPath($path, $mode = null, $recursive = true)
    {
        TypeCheck::doCheck(DataType::STRING, DataType::INT, DataType::BOOLEAN);

        if (is_null($mode)) {
            $mode = $this->defaultMode;
        }

        $finder = clone $this->finder;

        $finder->in($path);

        foreach ($finder->directories() as $directory) {
            $descriptor                          = inotify_add_watch(
                $this->resource,
                realpath($directory),
                $mode
            );
            $this->watchDescriptors[$descriptor] = realpath($directory);
        }

        echo realpath($path) . PHP_EOL;
        $descriptor                          = inotify_add_watch($this->resource, realpath($path), $mode);
        $this->watchDescriptors[$descriptor] = realpath($path);

        unset($finder);
    }

    /**
     * Remove all watched paths
     */
    public function clear()
    {
        foreach ($this->watchDescriptors as $descriptor => $path) {
            inotify_rm_watch($this->resource, $descriptor);
        }
    }
}
