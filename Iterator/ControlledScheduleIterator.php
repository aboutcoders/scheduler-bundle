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

use Abc\ProcessControl\ControllerInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ControlledScheduleIterator implements ScheduleIteratorInterface
{
    /** @var ControllerInterface */
    private $controller;
    /** @var ScheduleIteratorInterface */
    private $scheduleIterator;

    /**
     * @param ControllerInterface       $controller
     * @param ScheduleIteratorInterface $scheduleIterator
     */
    function __construct(ControllerInterface $controller, ScheduleIteratorInterface $scheduleIterator)
    {
        $this->controller       = $controller;
        $this->scheduleIterator = $scheduleIterator;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->scheduleIterator->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->scheduleIterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->scheduleIterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return !$this->controller->doExit() && $this->scheduleIterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->scheduleIterator->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->scheduleIterator->getManager();
    }
} 