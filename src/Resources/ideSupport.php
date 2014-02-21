<?php
/**
 * /src/ThinFrame/Inotify/ideSupport.php
 *
 * @link http://www.php.net/manual/en/book.inotify.php
 */


/**
 * Initialize an inotify instance for use with inotify_add_watch()
 *
 * @return resource
 */
function inotify_init()
{
    //noop
}

/**
 * inotify_add_watch() adds a new watch or modify an existing watch for the file or directory specified in pathname.
 *
 * @param resource $inotify_instance Resource returned by inotify_init()
 * @param string   $pathname         File or directory to watch
 * @param int      $mask             Events to watch for. See Predefined Constants.
 *
 * @return int unique (inotify instance wide) watch descriptor.
 */
function inotify_add_watch($inotify_instance, $pathname, $mask)
{
    //noop
}

/**
 * This function allows to know if inotify_read() will block or not. If a number
 * upper than zero is returned, there are pending events and inotify_read() will not block.
 *
 * @param resource $inotify_instance Resource returned by inotify_init()
 *
 * @return int Returns a number upper than zero if there are pending events.
 */
function inotify_queue_len($inotify_instance)
{
    //noop
}

/**
 * Read inotify events from an inotify instance.
 *
 * @param resource $inotify_instance Resource returned by inotify_init()
 *
 * @return array An array of inotify events or FALSE if no events was pending and inotify_instance is non-blocking.
 *              Each event is an array with the following keys:
 */
function inotify_read($inotify_instance)
{
    //noop
}

/**
 * inotify_rm_watch() removes the watch watch_descriptor from the inotify instance inotify_instance.
 *
 * @param resource $inotify_instance Resource returned by inotify_init()
 * @param int      $watch_descriptor Watch to remove from the instance
 *
 * @return bool TRUE on success or FALSE on failure.
 */
function inotify_rm_watch($inotify_instance, $watch_descriptor)
{
    //noop
}

/**
 * File was accessed (read)
 */
define('IN_ACCESS', 1);

/**
 * File was modified
 */
define('IN_MODIFY', 2);

/**
 * Metadata changed (e.g. permissions, mtime, etc.)
 */
define('IN_ATTRIB', 4);

/**
 * File opened for writing was closed
 */
define('IN_CLOSE_WRITE', 8);

/**
 * File not opened for writing was closed
 */
define('IN_CLOSE_NOWRITE', 16);

/**
 * File was opened
 */
define('IN_OPEN', 32);

/**
 * File moved into watched directory
 */
define('IN_MOVED_TO', 128);

/**
 * File moved out of watched directory
 */
define('IN_MOVED_FROM', 64);

/**
 * File or directory created in watched directory
 */
define('IN_CREATE', 256);

/**
 * File or directory deleted in watched directory
 */
define('IN_DELETE', 512);

/**
 * Watched file or directory was deleted
 */
define('IN_DELETE_SELF', 1024);

/**
 * Watch file or directory was moved
 */
define('IN_MOVE_SELF', 2048);

/**
 * Equals to IN_CLOSE_WRITE | IN_CLOSE_NOWRITE
 */
define('IN_CLOSE', 24);

/**
 * Equals to IN_MOVED_FROM | IN_MOVED_TO
 */
define('IN_MOVE', 192);

/**
 * Bitmask of all the above constants
 */
define('IN_ALL_EVENTS', 2095);

/**
 * File system containing watched object was unmounted
 */
define('IN_UNMOUNT', 8192);

/**
 * Event queue overflowed (wd is -1 for this event)
 */
define('IN_Q_OVERFLOW', 16384);

/**
 * Watch was removed (explicitly by inotify_rm_watch() or because file was removed or filesystem unmounted
 */
define('IN_IGNORED', 32768);

/**
 * Subject of this event is a directory
 */
define('IN_ISDIR', 1073741824);

/**
 * Only watch pathname if it is a directory (Since Linux 2.6.15)
 */
define('IN_ONLYDIR', 16777216);

/**
 * Do not dereference pathname if it is a symlink (Since Linux 2.6.15)
 */
define('IN_DONT_FOLLOW', 33554432);

/**
 * Add events to watch mask for this pathname if it already exists (instead of replacing mask).
 */
define('IN_MASK_ADD', 536870912);

/**
 * Monitor pathname for one event, then remove from watch list.
 */
define('IN_ONESHOT', 2147483648);
