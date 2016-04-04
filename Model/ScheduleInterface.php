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
 * ScheduleInterface defines when and how often something needs to be done.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
interface ScheduleInterface
{

    /**
     * @param string $type
     * @return void
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $expression A scheduling expression
     * @return void
     */
    public function setExpression($expression);

    /**
     * @return string A scheduling expression
     */
    public function getExpression();

    /**
     * @param \DateTime $scheduledAt
     * @return void
     */
    public function setScheduledAt($scheduledAt);

    /**
     * @return \DateTime|null
     */
    public function getScheduledAt();
}