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

/**
 *  A registry for objects of type ScheduleIteratorInterface
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface IteratorRegistryInterface
{
    /**
     * @param string $type
     * @param ScheduleIteratorInterface $iterator
     * @return void
     */
    public function register($type, ScheduleIteratorInterface $iterator);

    /**
     * @param string $type
     * @return ScheduleIteratorInterface
     * @throws \InvalidArgumentException
     */
    public function get($type);

    /**
     * @return array ScheduleIteratorInterface[]
     */
    public function all();
} 