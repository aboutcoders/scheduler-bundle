Scheduler Bundle
================

##Overview

This bundle allows you define schedules for recurring events and uses the [Symfony Event Dispatcher](http://symfony.com/doc/current/components/event_dispatcher/index.html) to dispatch an event whenever a schedule is due.

Take a look at the [job-bundle](https://github.com/aboutcoders/job-bundle) if you want to see a concrete usage of this bundle.

##Installation

The bundle should be installed through composer.

###Add the bundle to your composer.json file

```json
{
    "require": {
        "aboutcoders/scheduler-bundle": "~1.0"
    }
}
```

###Update AppKernel.php of your Symfony Application

Add the AbcSchedulerBundle to your kernel bootstrap sequence, in the `$bundles` array.

```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new \Abc\Bundle\SchedulerBundle\AbcSchedulerBundle(),
        // ...
    );
}
```

###Setup Schedule Command

The final step is to setup the schedule command. By executing this command schedules will be processed and an event will be dispatched whenever a schedule is due.

You can setup a cron job that is executed every minute

```shell
php app/console abc:scheduler:start
```

Alternatively you can put the command under a process control system and like supervisord to have an infinite running process

```shell
php app/console abc:scheduler:start --infinite
```

##Basic Configuration

At the current point only doctrine is supported as ORM. However by changing the configuration you can also use a different persistence layer.

Configure doctrine as database driver in config.yml

```yaml
abc_scheduler:
  db_driver: orm
```

##Setup Schedules

At this point the bundle is not fully working yet. You need to define your own Schedule entity classes and do some further configuration before you own schedules will be dispatched over the event dispatcher. The reason for this is that almost every application has different requirements on which additional information may be associated meaning is it for example a job that should be executed or a report which is created or something else.

#### Create you own Schedule class

Defining your own schedule class is easy. The bundle relies on doctrine's concept of a [mapped superclass](http://doctrine-orm.readthedocs.org/en/latest/reference/inheritance-mapping.html). Using this your own entity class inherits all the properties which are necessary to make them work as schedules.

Your Schedule class can live inside any bundle in your application. For example, if you work at "Acme" company, then you might create a bundle called AcmScheduleBundle and place your Schedule class in it.

If you're persisting your schedule via the Doctrine ORM, then your Schedule class should live in the Entity namespace of your bundle and look like this to start:

```php
namespace Acme\ScheduleBundle\Entity;

use Abc\Bundle\SchedulerBundle\Entity\Schedule as BaseSchedule;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="acme_schedule")
 * @ORM\Entity
 */
class Schedule extends BaseSchedule
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
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
```

Please refer to the doctrine documentation if you prefer to use YAML or XML over annotations to configure doctrine.

#### Register the schedule manager

The next step is to register the entity manager for the newly created schedule entity as a service within the service configuration. The easiest way to do this is by just using the implementation provided with this bundle. However you can of course provide your own implementation if you prefer.

In order to use the implementation provided with this bundle you just need to provide the following service configuration:

```xml
<service id="acme_schedule_manager" class="Abc\Bundle\SchedulerBundle\Doctrine\ScheduleManager">
    <argument type="service" id="doctrine.orm.entity_manager"/>
    <argument>Acme\ScheduleBundle\Entity\Schedule</argument>
</service>
```

With this you will use the doctrine implementation of a schedule manager which uses the default doctrine entity manager. Note that the second argument of the service references the fully qualified name of your Schedule class.

#### Register the schedule iterator

The next step is to register an iterator for the schedule manager. To so you only have to define another service and tag it.

```xml
<service id="acme_schedule_iterator" class="Abc\Bundle\SchedulerBundle\Iterator\ScheduleManagerScheduleIterator" public="true">
    <argument type="service" id="acme_schedule_manager"/>
    <tag name="abc.scheduler.iterator"/>
</service>
```

The first argument specifies the schedule manager to use, which in this case references the schedule manager we just defined. With the tag "abc.scheduler.iterator" this iterator gets registered within the bundle and thereby schedules will be continuously processed and an event gets notified when a schedule is due.

####Register a schedule listener

Whenever a schedule is due an event with the name "abc.schedule" of type Abc\Bundle\SchedulerBundle\Event\SchedulerEvent will be dispatched. So to actually get notified if one of your schedules is due you need to create a listener class.

```php
namespace Acme\ScheduleBundle\Listener;

use Abc\Bundle\SchedulerBundle\Event\SchedulerEvent;

class MyListener
{
    public function onSchedule(SchedulerEvent $event)
    {
        if($event->getSchedule() instanceof Acme\ScheduleBundle\Entity\Schedule)
        {
            // do something
        }
    }
}
```

This listener class finally needs to be registered within the service configuration.

```xml
<service id="acme_schedule_listener" class="Acme\ScheduleBundle\Listener\MyListener">
   <tag name="abc.scheduler.event_listener" method="onSchedule"/>
</service>
```

The method name specified in the attribute must match the name of the method in the listener class.

##Creating a schedule

A schedule is defined by its type and expression each being a string. At the current point there are two types supported: "cron" and "timestamp".

Use your previously registered schedule manager class to create a new schedule that will be executed every minute:

```php
$manager = $this->get('acme_schedule_manager');

$schedule = $manager->create();
$schedule->setType('cron');
$schedule->setExpression('* * * * *');
$manager->update($schedule);
```

#### CRON Schedules

Schedules of type "cron" are defined by cron expressions as known in LINUX bases systems. The bundle uses the [PHP Cron Expression Parser](https://github.com/mtdowling/cron-expression/) library to parse these expressions. The parts of a CRON schedule are as follows:

    *    *    *    *    *    *
    -    -    -    -    -    -
    |    |    |    |    |    |
    |    |    |    |    |    + year [optional]
    |    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
    |    |    |    +---------- month (1 - 12)
    |    |    +--------------- day of month (1 - 31)
    |    +-------------------- hour (0 - 23)
    +------------------------- min (0 - 59)

#### Timestamp Schedules

Schedules of type "timestamp" are executed only once. The expression for it must be a UNIX timestamp value.

##Registering Custom Schedule Processors

You can easily define your own schedule types and register a processor for it. To do you first need to create a class that implements the interface Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface.

This interface defines one method that you need to implement:

```php
interface ProcessorInterface
{
    /**
     * @param ScheduleInterface $schedule
     * @param \DateTime|null    $currentDateTime
     * @return boolean Whether the schedule is due or not
     */
    public function process(ScheduleInterface $schedule, \DateTime $currentDateTime = null);
}
```

The final step is to register this new processor. The only thing that needs to be done is to tag it:

```xml
<service id="acme_custom_processor" class="Acme\ScheduleBundle\Processor\MyProcessor">
   <tag name="abc.scheduler.processor" type="my_processor"/>
</service>
```

##ToDo
- How to use a PCNTL controlled iterator in a continuous deployment setup