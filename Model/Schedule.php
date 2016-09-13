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

use Abc\Bundle\SchedulerBundle\Validator\Constraints as AssertSchedule;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @AssertSchedule\Schedule
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class Schedule implements ScheduleInterface
{
    /**
     * @Assert\NotBlank
     * @AssertSchedule\Type
     * @var string
     */
    protected $type;

    /**
     * @Assert\NotBlank
     * @var string
     */
    protected $expression;

    /**
     * @var \DateTime
     */
    protected $scheduledAt;

    /**
     * {@inheritDoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * {@inheritDoc}
     */
    public function setScheduledAt($scheduledAt)
    {
        $this->scheduledAt = $scheduledAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getScheduledAt()
    {
        return $this->scheduledAt;
    }

    public function __clone()
    {
        $this->scheduledAt = null;
    }
}