<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Inotify;

use ThinFrame\Events\Dispatcher;
use ThinFrame\Events\DispatcherAwareInterface;
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
    private $inotifyStreamDescriptor = null;
    /**
     * @var array
     */
    private $watchDescriptors = [];
    /**
     * @var array
     */
    private $excluded = [];
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inotifyStreamDescriptor = inotify_init();
        stream_set_blocking($this->inotifyStreamDescriptor, 0);
        register_tick_function([$this, 'tick']);
    }

    /**
     * Tick handler
     */
    public function tick()
    {
        $events = inotify_read($this->inotifyStreamDescriptor);
        if (is_array($events)) {
            $this->handleEvents($events);
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
            $watchDescriptor = $event['wd'];
            $this->dispatcher->trigger(
                new InotifyEvent(['path' => $this->watchDescriptors[$watchDescriptor], 'inotify' => $event])
            );
        }
    }

    /**
     * Add a path to the watcher
     *
     * @param string $path
     * @param int    $mode
     * @param bool   $recursive
     */
    public function addPath($path, $mode = IN_MODIFY, $recursive = true)
    {
        TypeCheck::doCheck(DataType::STRING, DataType::INT, DataType::BOOLEAN);

        if ($this->isExcluded($path)) {
            return;
        }

        if (is_dir($path) && $recursive) {
            $children = scandir($path);
            foreach ($children as $child) {
                if (
                    $child != '.'
                    && $child != '..'
                    && $child != '.git'
                    && is_dir($path . DIRECTORY_SEPARATOR . $child)
                ) {
                    $this->addPath($path . DIRECTORY_SEPARATOR . $child, $mode, $recursive);
                }
            }
        }
        $watchDescriptor                          = inotify_add_watch($this->inotifyStreamDescriptor, $path, $mode);
        $this->watchDescriptors[$watchDescriptor] = $path;
    }

    /**
     * Check if path is excluded
     *
     * @param string $path
     *
     * @return bool
     */
    private function isExcluded($path)
    {
        return in_array(realpath($path), $this->excluded);
    }

    /**
     * Exclude path
     *
     * @param string $path
     *
     * @return $this
     */
    public function exclude($path)
    {
        TypeCheck::doCheck(DataType::STRING);
        if (realpath($path)) {
            $this->excluded[] = realpath($path);
        }

        return $this;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
