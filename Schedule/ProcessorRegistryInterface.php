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

/**
 * A registry for objects of type ProcessorInterface
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface ProcessorRegistryInterface
{
    /**
     * @param string             $type
     * @param ProcessorInterface $processor
     * @return void
     */
    public function register($type, ProcessorInterface $processor);

    /**
     * @param string $type
     * @return ProcessorInterface
     * @throws \InvalidArgumentException
     */
    public function get($type);
}