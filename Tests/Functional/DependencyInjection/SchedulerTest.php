<?php

namespace Abc\SchedulerBundle\Tests\Functional\DependencyInjection;

use Abc\Scheduler\Scheduler;
use Abc\SchedulerBundle\Tests\Functional\WebTestCase;

/**
 * @group functional
 */
class SchedulerTest extends WebTestCase
{
    public function testGetFromContainer()
    {
        $consumer = static::$container->get('abc.scheduler');
        $this->assertInstanceOf(Scheduler::class, $consumer);
    }
}
