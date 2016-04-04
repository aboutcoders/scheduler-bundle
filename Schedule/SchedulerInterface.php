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

use Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\SchedulerException;

/**
 * SchedulerInterface to process a set of schedules
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface SchedulerInterface
{

    /**
     * @param ScheduleIteratorInterface $scheduleIterator
     * @return int The number of schedules that were processed
     * @throws SchedulerException If processing of one or more schedules fails
     */
    public function process(ScheduleIteratorInterface $scheduleIterator);
} 