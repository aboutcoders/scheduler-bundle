<?php
/*
* This file is part of the job-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Validator\Constraints;

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Validator\Constraints as AssertSchedule;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintValidator;


/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$value instanceof ScheduleInterface) {
            throw new \InvalidArgumentException('The value must be an instance of ' . ScheduleInterface::class);
        }

        if(null == $value->getType()) {
            return;
        }

        $this->context->getValidator()
            ->inContext($this->context)
            ->atPath('expression')
            ->validate($value->getExpression(), new AssertSchedule\Expression(['type' => $value->getType()]));
    }
}