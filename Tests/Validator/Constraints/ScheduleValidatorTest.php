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

use Abc\Bundle\SchedulerBundle\Validator\Constraints\Expression;
use Abc\Bundle\SchedulerBundle\Validator\Constraints\Schedule;
use Abc\Bundle\SchedulerBundle\Validator\Constraints\ScheduleValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleValidatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ExecutionContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * @var ScheduleValidator
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->subject = new ScheduleValidator();
        $this->context = $this->getMockBuilder(ExecutionContext::class)->disableOriginalConstructor()->getMock();
        $this->subject->initialize($this->context);
    }

    public function testValidateWithNull()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate(null, new Schedule());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWithWrongInstance()
    {
        $this->subject->validate(new \stdClass(), new Schedule());
    }

    public function testValidateWithTypeIsNull()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate(new \Abc\Bundle\SchedulerBundle\Model\Schedule(), new Schedule());
    }

    public function testValidateWithType()
    {
        $value = new \Abc\Bundle\SchedulerBundle\Model\Schedule();
        $value->setType('foobar');
        $value->setExpression('ScheduleExpression');

        $validator           = $this->getMock(ValidatorInterface::class);
        $contextualValidator = $this->getMock(ContextualValidatorInterface::class);

        $this->context->expects($this->once())
            ->method('getValidator')
            ->willReturn($validator);

        $validator->expects($this->once())
            ->method('inContext')
            ->with($this->context)
            ->willReturn($contextualValidator);

        $contextualValidator->expects($this->once())
            ->method('atPath')
            ->with('expression')
            ->willReturn($contextualValidator);

        $contextualValidator->expects($this->once())
            ->method('validate')
            ->with('ScheduleExpression', new Expression(['type' => $value->getType()]));

        $this->subject->validate($value, new Schedule());
    }
}