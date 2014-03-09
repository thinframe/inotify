<?php

/**
 * /src/InotifyApplication.php
 *
 * @author Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Inotify;

use PhpCollection\Map;
use ThinFrame\Applications\AbstractApplication;
use ThinFrame\Applications\DependencyInjection\ContainerConfigurator;
use ThinFrame\Events\EventsApplication;

/**
 * Class InotifyApplication
 *
 * @package ThinFrame\Inotify
 * @since   0.2
 */
class InotifyApplication extends AbstractApplication
{
    /**
     * Get application name
     *
     * @return string
     */
    public function getName()
    {
        return $this->reflector->getShortName();
    }

    /**
     * Get application parents
     *
     * @return AbstractApplication[]
     */
    public function getParents()
    {
        return [
            new EventsApplication()
        ];
    }

    /**
     * Set different options for the container configurator
     *
     * @param ContainerConfigurator $configurator
     */
    protected function setConfiguration(ContainerConfigurator $configurator)
    {
        $configurator->addResource('Resources/config/services.yml');
    }

    /**
     * Set application metadata
     *
     * @param Map $metadata
     *
     */
    protected function setMetadata(Map $metadata)
    {
        //noop
    }
}

