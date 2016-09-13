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

use Abc\Bundle\SchedulerBundle\Schedule\ProcessorRegistryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class TypeValidator extends ConstraintValidator
{
    /**
     * @var ProcessorRegistryInterface
     */
    private $registry;

    /**
     * @param ProcessorRegistryInterface $registry
     */
    public function __construct(ProcessorRegistryInterface $registry)
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

        if (!$this->registry->has($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{string}}', $value)
                ->addViolation();
        }
    }
}