<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\SchedulerBundle\Tests\Integration;

use Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleManagerInterface;
use Abc\Bundle\SchedulerBundle\Schedule\SchedulerInterface;
use Abc\DemoBundle\Entity\Schedule;
use Abc\DemoBundle\Listener\DemoListener;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class BundleIntegrationTest extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container = static::$kernel->getContainer();

        $this->em = $this->container->get('doctrine')->getManager();

        $this->application = new Application(static::$kernel);

        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);

        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:update", array("--force" => true));
    }

    public function testScheduleInheritanceMapping()
    {
        /**
         * @var ScheduleManagerInterface $manager
         */
        $manager = $this->container->get('abc.demo.schedule_manager');

        $schedule = $manager->create();
        $schedule->setExpression('* * * * *');
        $schedule->setType('cron');

        $manager->save($schedule);

        $this->em->clear();

        $schedules = $manager->findSchedules();

        $this->assertCount(1, $schedules);

        /**
         * @var Schedule $schedule
         */
        $schedule = $schedules[0];

        $this->assertInstanceOf(ScheduleInterface::class, $schedule);
        $this->assertEquals('* * * * *', $schedule->getExpression());
    }


    public function testListenersAreNotified()
    {
        /**
         * @var ScheduleManagerInterface $manager
         */
        $manager = $this->container->get('abc.demo.schedule_manager');

        $schedule = $manager->create();
        $schedule->setExpression('* * * * *');

        $manager->save($schedule);

        /**
         * @var ScheduleIteratorInterface $iterator
         */
        $iterator = $this->container->get('abc.demo.iterator');

        /**
         * @var SchedulerInterface $scheduler
         */
        $scheduler = $this->container->get('abc.scheduler.scheduler');

        /**
         * @var DemoListener $listener
         */
        $listener = $this->container->get('abc.demo.listener');

        $scheduler->process($iterator);

        $events = $listener->getEvents();
        $this->assertCount(1, $events);
        $this->assertSame($schedule, $events[0]->getSchedule());
    }

    protected function runConsole($command, array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options       = array_merge($options, array('command' => $command));

        return $this->application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }
}