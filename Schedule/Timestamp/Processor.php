<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Schedule\Timestamp;

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface;

/**
 * Checks whether a schedule with a timestamp is due.
 *
 * @author Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
 */
class Processor implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ScheduleInterface $schedule, \DateTime $currentDateTime = null)
    {
        $now = ($currentDateTime == null) ? new \DateTime() : $currentDateTime;

        if($schedule->getScheduledAt() !== null)
        {
            return false;
        }

        if($schedule->getExpression() < $now->getTimestamp())
        {
            return true;
        }

        return false;
    }
}