<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Validator\Constraints;

use Abc\Bundle\SchedulerBundle\Validator\Constraints\CronExpression;
use Abc\Bundle\SchedulerBundle\Validator\Constraints\CronExpressionValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class CronExpressionValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExecutionContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * @var CronExpressionValidator
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->subject = new CronExpressionValidator();
        $this->context = $this->createMock(ExecutionContext::class);
        $this->subject->initialize($this->context);
    }

    public function testValidateWithNull()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate(null, new CronExpression());
    }

    /**
     * @dataProvider provideValidExpressions
     * @param string $expression
     */
    public function testValidateWithValidExpression($expression)
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate($expression, new CronExpression());
    }

    /**
     * @dataProvider provideInvalidExpressions
     * @param string $expression
     */
    public function testValidateWithInvalidExpression($expression)
    {
        $builder = $this->createMock(ConstraintViolationBuilderInterface::class);

        $this->context->expects($this->once())
            ->method('buildViolation')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('setParameter')
            ->with('{{string}}', $expression)
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('addViolation');

        $this->subject->validate($expression, new CronExpression());
    }

    /**
     * @return array
     */
    public static function provideValidExpressions()
    {
        return [
            ['* * * * *'],
            ['1 * * * *'],
            ['*/5 * * * *']
        ];
    }

    /**
     * @return array
     */
    public static function provideInvalidExpressions()
    {
        return [
            ['*'],
            ['a a a a a']
        ];
    }
}