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

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleException
{
    /**
     * @var ScheduleInterface
     */
    private $schedule;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @param ScheduleInterface $schedule
     * @param \Exception        $exception
     */
    public function __construct(ScheduleInterface $schedule, \Exception $exception)
    {
        $this->schedule = $schedule;
        $this->exception = $exception;
    }

    /**
     * @return ScheduleInterface
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}