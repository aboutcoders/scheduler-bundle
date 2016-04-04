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

use Cron\CronExpression;

/**
 * ExpressionFactory
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ExpressionFactory implements ExpressionFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create($expression)
    {
        return CronExpression::factory($expression);
    }
}