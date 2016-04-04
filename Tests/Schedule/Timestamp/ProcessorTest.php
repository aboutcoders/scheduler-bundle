<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Schedule\Timestamp;

use Abc\Bundle\SchedulerBundle\Schedule\Timestamp\Processor;
use Abc\Bundle\SchedulerBundle\Model\Schedule;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{

    /** @var Processor */
    private $subject;

    /**
     * @before
     */
    public function setupSubject()
    {
        $this->subject = new Processor();
    }

    /**
     * @param int            $expression
     * @param boolean        $isDue
     * @param \DateTime|null $currentDateTime
     * @dataProvider getExpectedData
     */
    public function testProcessReturnsExpectedData($expression, $isDue, \DateTime $currentDateTime = null)
    {
        $schedule = new Schedule();
        $schedule->setExpression($expression);

        $this->assertEquals($isDue, $this->subject->process($schedule, $currentDateTime));
    }

    /**
     * @param \DateTime|null $currentDateTime
     * @dataProvider getDatesData
     */
    public function testProcessWithScheduledScheduleReturnsFalse(\DateTime $currentDateTime = null)
    {
        $schedule = new Schedule();
        $schedule->setScheduledAt(new \DateTime());

        $this->assertFalse($this->subject->process($schedule, $currentDateTime));
    }


    public static function getExpectedData()
    {
        $date            = new \DateTime();

        $dateDue = new \DateTime();
        $dateDue->sub(new \DateInterval('P1D'));

        $dateNotDue = new \DateTime();
        $dateNotDue->add(new \DateInterval('P1D'));

        return array(
            array($dateDue->getTimestamp(), true),
            array($dateNotDue->getTimestamp(), false),
            array($dateDue->getTimestamp(), true, $date),
            array($dateNotDue->getTimestamp(), false, $date)
        );
    }

    public static function getDatesData()
    {
        return array(
            array(null),
            array(new \DateTime())
        );
    }
}
 