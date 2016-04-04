<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/*
 * This script performs iterations unless a PCNTL signal of type SIGALRM is sent.
 *
 * Depending on whether the iteration is aborted or not the string "iteration finished" or "iteration aborted" is echoed.
 */
require_once dirname(__FILE__).'/../../../vendor/autoload.php';

use Abc\Bundle\SchedulerBundle\Iteration\PcntlIterationControl;

$iterationControl = new PcntlIterationControl(array(SIGALRM));

echo('start iterating');
$iterations = 0;
while(!$iterationControl->stop() && $iterations <=100)
{
    echo('...');
    usleep(5000);
    $iterations++;
}

if($iterations < 100){
    echo('iteration aborted');
}
else
{
    echo('iteration finished');
}