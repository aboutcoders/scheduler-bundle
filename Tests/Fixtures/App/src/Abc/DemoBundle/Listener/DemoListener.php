<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\DemoBundle\Listener;

use Abc\Bundle\SchedulerBundle\Event\SchedulerEvent;
use Abc\DemoBundle\Entity\Schedule;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class DemoListener
{
    protected $events = array();


    public function onSchedule(SchedulerEvent $event)
    {
        if($event->getSchedule() instanceof Schedule)
        {
            $this->events[] = $event;
        }
    }

    public function clear()
    {
        $this->events = array();
    }

    public function getEvents()
    {
        return $this->events;
    }
}