<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Doctrine;

use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManager as BaseScheduleManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 *  ScheduleManager manages doctrine entities.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManager extends BaseScheduleManager
{
    /** @var string */
    protected $class;
    /** @var ObjectManager */
    protected $objectManager;
    /** @var ObjectRepository */
    protected $repository;

    /**
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository    = $om->getRepository($class);

        $metadata    = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * Updates a schedule
     *
     * @param ScheduleInterface $schedule
     * @param Boolean           $andFlush Whether to flush the changes (default true)
     */
    public function save(ScheduleInterface $schedule, $andFlush = true)
    {
        $this->objectManager->persist($schedule);
        if ($andFlush)
        {
            $this->objectManager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function findSchedules($limit = null, $offset = null)
    {
        return $this->repository->findBy(array(), array(), $limit, $offset);
    }
}