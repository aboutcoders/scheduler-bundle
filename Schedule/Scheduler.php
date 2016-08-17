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

use Abc\Bundle\SchedulerBundle\Event\SchedulerEvent;
use Abc\Bundle\SchedulerBundle\Event\SchedulerEvents;
use Abc\Bundle\SchedulerBundle\Iterator\ScheduleIteratorInterface;
use Abc\Bundle\SchedulerBundle\Model\ScheduleInterface;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\ScheduleException;
use Abc\Bundle\SchedulerBundle\Schedule\Exception\SchedulerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Scheduler iterates over schedules and notifies a SchedulerEvent if a schedule is due.
 *
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class Scheduler implements SchedulerInterface
{
    /**
     * @var ProcessorRegistryInterface
     */
    protected $registry;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ProcessorRegistryInterface $registry
     * @param EventDispatcherInterface   $dispatcher
     * @param LoggerInterface            $logger
     */
    public function __construct(ProcessorRegistryInterface $registry, EventDispatcherInterface $dispatcher, LoggerInterface $logger = null)
    {
        $this->registry   = $registry;
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger == null ? new NullLogger() : $logger;
    }

    /**
     * {@inheritDoc}
     */
    public final function process(ScheduleIteratorInterface $iterator)
    {
        $numOfProcessed = 0;
        $exceptions = array();
        foreach($iterator as $schedule)
        {
            $this->logger->debug('Process schedule {schedule}', array('schedule' => $schedule));

            try
            {
                /**
                 * @var ScheduleInterface $schedule
                 */
                if($this->registry->get($schedule->getType())->process($schedule))
                {
                    $schedule->setScheduledAt(new \DateTime());
                    $iterator->getManager()->save($schedule);

                    $this->dispatcher->dispatch(SchedulerEvents::SCHEDULE, new SchedulerEvent($schedule));

                    $numOfProcessed++;
                }
            }
            catch(\Exception $e)
            {
                $this->logger->error('Failed to process schedule {schedule} ({exception})', array('schedule' => $schedule, 'exception' => $e));
                $exceptions[] = new ScheduleException($schedule, $e);
            }
        }

        if(count($exceptions) > 0)
        {
            throw new SchedulerException($numOfProcessed, $exceptions);
        }

        return $numOfProcessed;
    }
}