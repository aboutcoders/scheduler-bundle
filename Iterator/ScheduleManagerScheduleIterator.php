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

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;

/**
 * Iterator based on ScheduleManagerInterface.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManagerScheduleIterator implements ScheduleIteratorInterface
{

    /**
     * @var ScheduleManagerInterface
     */
    protected $scheduleManager;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var ScheduleInterface
     */
    protected $current;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var array
     */
    protected $buffer = array();


    /**
     * @param ScheduleManagerInterface $scheduleManager
     * @param int                      $batchSize (default is 100)
     */
    public function __construct(ScheduleManagerInterface $scheduleManager, $batchSize = 100)
    {
        $this->scheduleManager = $scheduleManager;
        $this->limit           = $batchSize;
        $this->offset          = 0;
        $this->position        = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->scheduleManager;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
        $this->offset = 0;
        $this->setCurrent();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->current != null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->setCurrent();
        $this->position++;
    }

    /**
     * Assign current pointer
     */
    protected function setCurrent()
    {
        if(count($this->buffer) === 0)
        {
            $this->buffer();
        }

        $this->current = count($this->buffer) > 0 ? array_pop($this->buffer) : null;
    }

    /**
     * Fill the inner buffer
     */
    protected function buffer()
    {
        $this->buffer = $this->scheduleManager->findSchedules($this->limit, $this->offset);

        $this->offset += count($this->buffer);
    }
}