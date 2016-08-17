<?php
/*
* This file is part of the scheduler-bundle package.
*
* (c) Hannes Schulz <hannes.schulz@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\DemoBundle\Entity;

use Abc\Bundle\SchedulerBundle\Model\Schedule as BaseSchedule;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Table(name="abc_demo_schedule")
 * @Entity
 * @author Hannes Schulz <hannes.schulz@aboutcoders.com>
 */
class Schedule extends BaseSchedule
{
    /**
     * @var string
     */
    protected $type = 'cron';

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}