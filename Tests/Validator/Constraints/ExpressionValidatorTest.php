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

use Abc\Bundle\SchedulerBundle\Validator\ConstraintRegistry;
use Abc\Bundle\SchedulerBundle\Validator\Constraints\Expression;
use Abc\Bundle\SchedulerBundle\Validator\Constraints\ExpressionValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Validator\ContextualValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ExpressionValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintRegistry|\PHPUnit_Framework_MockObject_MockObject
     */
    private $registry;

    /**
     * @var ExecutionContext|\PHPUnit_Framework_MockObject_MockObject
     */
    private $context;

    /**
     * @var ExpressionValidator
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->registry = $this->createMock(ConstraintRegistry::class);
        $this->subject  = new ExpressionValidator($this->registry);
        $this->context  = $this->createMock(ExecutionContext::class);
        $this->subject->initialize($this->context);
    }

    public function testValidateWithNull()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate(null, new Expression(['type' => 'foobar']));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testValidateThrowsExceptionIfTypeNotSet()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate('foobar', new Expression());
    }

    public function testValidateWithTypeNotRegistered()
    {
        $this->context->expects($this->never())
            ->method('buildViolation');

        $this->subject->validate('foobar', new Expression(['type' => 'whatever']));
    }

    public function testValidateWithTypeRegistered()
    {
        $validator           = $this->createMock(ValidatorInterface::class);
        $contextualValidator = $this->createMock(ContextualValidatorInterface::class);
        $constraint          = $this->createMock(Constraint::class);

        $this->registry->expects($this->once())
            ->method('has')
            ->with('ScheduleType')
            ->willReturn(true);

        $this->registry->expects($this->once())
            ->method('get')
            ->with('ScheduleType')
            ->willReturn($constraint);

        $this->context->expects($this->once())
            ->method('getValidator')
            ->willReturn($validator);

        $validator->expects($this->once())
            ->method('inContext')
            ->with($this->context)
            ->willReturn($contextualValidator);

        $contextualValidator->expects($this->once())
            ->method('validate')
            ->with('foobar', $constraint);

        $this->subject->validate('foobar', new Expression(['type' => 'ScheduleType']));
    }
}