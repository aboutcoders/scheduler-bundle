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
 * ScheduleManagerInterface to perform CRUD operations with entities of type ScheduleInterface.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface ScheduleManagerInterface
{
    /**
     * Returns an empty schedule instance.
     *
     * @return ScheduleInterface
     */
    public function create();

    /**
     * @param ScheduleInterface $schedule
     * @return void
     */
    public function save(ScheduleInterface $schedule);

    /**
     * Returns the schedule's fully qualified class name.
     *
     * @return string
     */
    public function getClass();

    /**
     * Finds schedules by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findSchedules($limit = null, $offset = null);

}