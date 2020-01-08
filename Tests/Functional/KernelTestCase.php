<?php

namespace Abc\SchedulerBundle\Tests\Functional;

use Abc\JobWorkerBundle\Tests\Functional\App\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as BaseKernelTestCase;

class KernelTestCase extends BaseKernelTestCase
{
    /**
     * @return string
     */
    public static function getKernelClass()
    {
        include_once __DIR__.'/App/AppKernel.php';

        return AppKernel::class;
    }
}
