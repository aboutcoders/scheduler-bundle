<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ConstraintRegistry
{
    /**
     * @var array
     */
    private $constraints = [];

    /**
     * @param string                  $type
     * @param Constraint|Constraint[] $constraint
     */
    public function register($type, $constraint)
    {
        $this->constraints[$type] = $constraint;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function has($type)
    {
        return isset($this->constraints[$type]);
    }

    /**
     * @param string $type
     * @return Constraint|Constraint[]
     */
    public function get($type)
    {
        if (!array_key_exists($type, $this->constraints)) {
            throw new \InvalidArgumentException(sprintf('A validation constraint for type "%s" is not registered', $type));
        }

        return $this->constraints[$type];
    }
}