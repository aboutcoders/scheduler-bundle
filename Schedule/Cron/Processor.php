<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Schedule\Cron;

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface;
use Cron\CronExpression;

/**
 * Checks whether a schedule with a cron expression is due using the library "PHP Cron Expression Parser" by mtdowling.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 * @see https://github.com/mtdowling/cron-expression
 */
class Processor implements ProcessorInterface
{
    /** @var ExpressionFactoryInterface */
    protected $expressionFactory;

    /**
     * @param ExpressionFactoryInterface $expressionFactory
     */
    function __construct(ExpressionFactoryInterface $expressionFactory)
    {
        $this->expressionFactory = $expressionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ScheduleInterface $schedule, \DateTime $currentDateTime = null)
    {
        // ensure that a schedule is not executed twice within in a minute
        $now = ($currentDateTime == null) ? new \DateTime() : $currentDateTime;

        if($schedule->getScheduledAt() != null && $schedule->getScheduledAt()->format('Y-m-d H:i') == $now->format('Y-m-d H:i'))
        {
            return false;
        }

        $cron = $this->expressionFactory->create($schedule->getExpression());

        return $cron->isDue($currentDateTime);
    }
}