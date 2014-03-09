<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Inotify;

use ThinFrame\Events\AbstractEvent;

/**
 * InotifyEvent
 *
 * @package ThinFrame\Inotify
 * @since   0.2
 */
class InotifyEvent extends AbstractEvent
{
    /**
     * Constructor
     *
     * @param array $payload
     */
    public function __construct(array $payload = [])
    {
        parent::__construct('thinframe.inotify', $payload);
    }
}
