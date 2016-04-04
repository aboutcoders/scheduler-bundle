<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Schedule;

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;

/**
 * ProcessorInterface to determine whether a schedule is due for notification.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface ProcessorInterface
{
    /**
     * @param ScheduleInterface $schedule
     * @param \DateTime|null    $currentDateTime
     * @return boolean Whether the schedule is due or not
     */
    public function process(ScheduleInterface $schedule, \DateTime $currentDateTime = null);
}