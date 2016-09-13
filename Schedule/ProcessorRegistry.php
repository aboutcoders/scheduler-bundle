<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Schedule;

/**
 * ProcessorRegistry
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ProcessorRegistry implements ProcessorRegistryInterface
{
    /**
     * @var array
     */
    private $registry = [];

    /**
     * {@inheritDoc}
     */
    public function register($type, ProcessorInterface $processor)
    {
        $this->registry[$type] = $processor;
    }

    /**
     * {@inheritDoc}
     */
    public function has($type)
    {
        return isset($this->registry[$type]);
    }

    /**
     * {@inheritDoc}
     */
    public function get($type)
    {
        if(!array_key_exists($type, $this->registry))
        {
            throw new \InvalidArgumentException(sprintf('A processor for type "%s" is not registered', $type));
        }

        return $this->registry[$type];
    }
}