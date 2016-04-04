<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Event;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
final class SchedulerEvents
{
    /**
     * The abc.schedule event is triggered each time a schedule is due
     *
     * The event listener receives an Abc\Bundle\SchedulerBundle\Event\Schedule instance.
     *
     * @var string
     */
    const SCHEDULE = 'abc.schedule';
}