<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Doctrine;

use Abc\Bundle\SchedulerBundle\Doctrine\ScheduleManager;
use Abc\Bundle\SchedulerBundle\Model\Schedule;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class ScheduleManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var ClassMetadata|\PHPUnit_Framework_MockObject_MockObject
     */
    private $classMetaData;

    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    /**
     * @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var ScheduleManager
     */
    private $subject;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->class         = Schedule::class;
        $this->classMetaData = $this->createMock(ClassMetadata::class);
        $this->objectManager = $this->createMock(ObjectManager::class);
        $this->repository    = $this->createMock(ObjectRepository::class);

        $this->objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($this->classMetaData));

        $this->classMetaData->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($this->class));

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->subject = new ScheduleManager($this->objectManager, $this->class);
    }

    public function testGetClass()
    {
        $this->assertEquals($this->class, $this->subject->getClass());
    }

    public function testSave()
    {
        $entity = $this->subject->create();

        $this->objectManager->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->objectManager->expects($this->once())
            ->method('flush');

        $this->subject->save($entity);
    }

    public function testSaveWithFlush()
    {
        $entity = $this->subject->create();

        $this->objectManager->expects($this->once())
            ->method('persist')
            ->with($entity);

        $this->objectManager->expects($this->never())
            ->method('flush');

        $this->subject->save($entity, false);
    }

    public function testFindSchedules()
    {
        $limit    = 2;
        $offset   = 1;

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(array(), array(), $limit, $offset);

        $this->subject->findSchedules($limit, $offset);
    }
}