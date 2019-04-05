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

use Abc\Bundle\SchedulerBundle\Model\Schedule;
use Abc\Bundle\SchedulerBundle\Schedule\Cron\ExpressionFactory;
use Abc\Bundle\SchedulerBundle\Schedule\Cron\ExpressionFactoryInterface;
use Abc\Bundle\SchedulerBundle\Schedule\Cron\Processor;
use Cron\CronExpression;
use PHPUnit\Framework\TestCase;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ProcessorTest extends TestCase
{
    /**
     * @var ExpressionFactoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $factory;

    /**
     * @var CronExpression|\PHPUnit_Framework_MockObject_MockObject
     */
    private $expression;

    /**
     * @var Processor
     */
    private $subject;

    public function setUp()
    {
        $this->factory    = $this->createMock(ExpressionFactoryInterface::class);
        $this->expression = $this->createMock(CronExpression::class);
        $this->subject    = new Processor($this->factory);
    }


    /**
     * @param boolean        $isDue
     * @param \DateTime|null $currentDateTime
     * @dataProvider getBoolean
     */
    public function testProcessReturnsIsDue($isDue, \DateTime $currentDateTime = null)
    {
        $schedule = new Schedule();
        $schedule->setExpression('* * * * *');

        $this->factory->expects($this->once())
            ->method('create')
            ->with($schedule->getExpression())
            ->will($this->returnValue($this->expression));

        $this->expression->expects($this->once())
            ->method('isDue')
            ->with($currentDateTime)
            ->will($this->returnValue($isDue));

        $this->assertEquals($isDue, $this->subject->process($schedule, $currentDateTime));
    }

    public function testProcessNotReturnsTrueTwiceWithinOneMinute()
    {
        $schedule = new Schedule();
        $schedule->setExpression('* * * * *');
        $schedule->setScheduledAt(new \DateTime('2010-01-01 00:00:00'));

        $this->factory->expects($this->any())
            ->method('create')
            ->with($schedule->getExpression())
            ->will($this->returnValue($this->expression));

        $this->expression->expects($this->any())
            ->method('isDue')
            ->will($this->returnValue(true));

        $subject = new Processor(new ExpressionFactory());
        $this->assertFalse($subject->process($schedule, new \DateTime('2010-01-01 00:00:00')));
    }

    public static function getBoolean()
    {
        return array(
            array(true),
            array(false),
            array(true, new \DateTime()),
            array(false, new \DateTime())
        );
    }
}