<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Schedule\Exception;

/**
 * SchedulerException to be thrown by scheduler if processing of one or more schedules fails.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class SchedulerException extends \Exception
{
    private $numOfProcessed;
    private $scheduleExceptions;


    public function __construct($numOfProcessed, array $scheduleExceptions)
    {
        $this->numOfProcessed = $numOfProcessed;
        $this->scheduleExceptions = $scheduleExceptions;
    }

    /**
     * @return string
     */
    public function getNumOfProcessed()
    {
        return $this->numOfProcessed;
    }

    /**
     * @return array ScheduleException[] An array containing objects of type Abc\Bundle\SchedulerBundle\Schedule\Exception\ScheduleException
     */
    public function getScheduleExceptions()
    {
        return $this->scheduleExceptions;
    }
}