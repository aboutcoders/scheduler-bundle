<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Functional\Validator;

use Abc\Bundle\SchedulerBundle\Model\Schedule;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        self::bootKernel();
        $this->validator = static::$kernel->getContainer()->get('validator');
    }

    public function testWithEmptySchedule()
    {
        $this->assertCount(2, $this->validator->validate(static::createSchedule()));
    }

    public function testWithTypeNull()
    {
        $this->assertCount(1, $this->validator->validate(static::createSchedule(null, 'foobar')));
    }

    public function testWithExpressionNull()
    {
        $errors = $this->validator->validate(static::createSchedule('foobar', null));

        $this->assertCount(2, $errors);
        $this->assertEquals('type', $errors->get(0)->getPropertyPath());
        $this->assertEquals('The value "foobar" is not valid schedule type.', $errors->get(0)->getMessage());
        $this->assertEquals('expression', $errors->get(1)->getPropertyPath());
        $this->assertEquals('This value should not be blank.', $errors->get(1)->getMessage());
    }

    /**
     * @dataProvider provideTypeAndExpression
     * @param string      $expression
     * @param string|null $errorMessage
     */
    public function testWithTypeAndExpression($type, $expression, $errorMessage = null)
    {
        $errors = $this->validator->validate(static::createSchedule($type, $expression));

        $this->assertCount($errorMessage == null ? 0 : 1, $errors);
        if (null != $errorMessage) {
            $this->assertEquals('expression', $errors->get(0)->getPropertyPath());
            $this->assertEquals($errorMessage, $errors->get(0)->getMessage());
        }
    }

    /**
     * @return array
     */
    public static function provideTypeAndExpression()
    {
        return [
            ['cron', '* * * * *',],
            ['cron', 'foobar', 'The value "foobar" is not a valid cron expression.'],
            ['timestamp', 1],
            ['timestamp', 'abc', 'This value should be a valid number.'],
            ['timestamp', 0, 'This value should be 1 or more.']
        ];
    }

    /**
     * @param string|null $type
     * @param string|null $expression
     * @return Schedule
     */
    public static function createSchedule($type = null, $expression = null)
    {
        $schedule = new Schedule();
        $schedule->setType($type);
        $schedule->setExpression($expression);

        return $schedule;
    }
}