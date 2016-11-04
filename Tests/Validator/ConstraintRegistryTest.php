<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Validator;

use Abc\Bundle\SchedulerBundle\Validator\ConstraintRegistry;
use Symfony\Component\Validator\Constraint;

class ConstraintRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConstraintRegistry
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->subject = new ConstraintRegistry();
    }

    public function testRegister()
    {
        $constraint = $this->createMock(Constraint::class);

        $this->subject->register('foobar', $constraint);
        $this->assertSame($constraint, $this->subject->get('foobar'));
    }

    public function testHas()
    {
        $this->assertFalse($this->subject->has('foobar'));
        $this->subject->register('foobar', $this->createMock(Constraint::class));
        $this->assertTrue($this->subject->has('foobar'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetThrowsInvalidArgumentException()
    {
        $this->subject->get('foo');
    }
}