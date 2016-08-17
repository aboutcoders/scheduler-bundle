<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Iterator;

use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;

/**
 * ScheduleIteratorInterface to iterate over a set of schedules
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface ScheduleIteratorInterface extends \Iterator
{
    /**
     * @return ScheduleManagerInterface
     */
    public function getManager();
}