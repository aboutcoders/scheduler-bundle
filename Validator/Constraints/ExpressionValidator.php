<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Validator\Constraints;

use Abc\Bundle\SchedulerBundle\Validator\ConstraintRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ExpressionValidator extends ConstraintValidator
{
    /**
     * @var ConstraintRegistry
     */
    private $registry;

    /**
     * @param ConstraintRegistry $registry
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        if (!$constraint->type) {
            throw new ConstraintDefinitionException('"type" must be specified on constraint Expression');
        }

        if(!$this->registry->has($constraint->type)) {
            return;
        }

        $this->context->getValidator()
            ->inContext($this->context)
            ->validate($value, $this->registry->get($constraint->type));
    }
}