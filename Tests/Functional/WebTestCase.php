<?php

namespace Abc\SchedulerBundle\Tests\Functional;

use Abc\JobWorkerBundle\Tests\Functional\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class WebTestCase extends BaseWebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var ContainerInterface
     */
    protected static $container;

    protected function setUp()
    {
        parent::setUp();
        static::$class = null;
        static::$client = static::createClient();
        static::$container = static::$container ?: static::$kernel->getContainer();
    }

    protected function tearDown(): void
    {
        static::$client = null;
        static::$kernel = null;
        static::$container = null;
    }

    /**
     * @return string
     */
    public static function getKernelClass()
    {
        include_once __DIR__.'/App/AppKernel.php';

        return AppKernel::class;
    }
}
