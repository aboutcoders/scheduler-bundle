<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Iterator;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class IteratorRegistry implements IteratorRegistryInterface
{
    private $registry = [];

    /**
     * {@inheritDoc}
     */
    public function register($name, ScheduleIteratorInterface $processor)
    {
        $this->registry[$name] = $processor;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->registry)) {
            throw new \InvalidArgumentException(sprintf('An iterator for type "%s" is not registered', $name));
        }

        return $this->registry[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function all()
    {
        return $this->registry;
    }
}