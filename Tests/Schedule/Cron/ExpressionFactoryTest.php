<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Schedule\Cron;

use Abc\Bundle\SchedulerBundle\Schedule\Cron\ExpressionFactory;
use Cron\CronExpression;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ExpressionFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $factory = new ExpressionFactory();
        $subject = $factory->create('* * * * *');

        $this->assertInstanceOf(CronExpression::class, $subject);
        $this->assertTrue($subject->isDue());
    }
}