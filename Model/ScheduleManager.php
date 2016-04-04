<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Model;

/**
 * ScheduleManager manages entities of type ScheduleInterface.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
abstract class ScheduleManager implements ScheduleManagerInterface
{
    /**
     * {@inheritDoc}
     */
    public function create()
    {
        $class = $this->getClass();

        $schedule = new $class;

        return $schedule;
    }
} 