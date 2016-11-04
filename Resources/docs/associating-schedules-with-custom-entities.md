Associating Schedules With Custom Entities
==========================================

At this point the bundle is not fully working yet. You need to define your own Schedule entity classes and do some further configuration before you own schedules will be dispatched with the [Symfony Event Dispatcher](http://symfony.com/doc/current/components/event_dispatcher/index.html). The reason for this is that almost every application has different requirements on which additional attributes must be associated with a schedule.

This involves the following steps:

1. Create the schedule entity class
2. Register the schedule's entity manager
3. Register the schedule iterator
4. Define and register a listener
5. Setup the scheduler command

## Step 1: Create the schedule entity class

Defining your own schedule entity is easy. The bundle relies on doctrine's concept of a [Mapped Superclass](http://doctrine-orm.readthedocs.org/en/latest/reference/inheritance-mapping.html). With this your entity class will inherit all the required attributes to make them work as schedules.

Your Schedule class can live inside any bundle within your application. For example, if you work at "Acme" company, then you might create a bundle called AcmSchedulerBundle and define your Schedule entity class in that.

Assuming you are using the Doctrine ORM (which is the only supported persistence layer at this point), then your Schedule class should live in the Entity namespace of your bundle and look something like this:

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
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
```

Please refer to the [official documentation](http://doctrine-orm.readthedocs.org) of doctrine if you prefer to use YAML or XML over annotations.

## Step 2: Register the schedule's entity manager

The next step is to register the entity manager for the newly created Schedule entity as a service within the service configuration. The easiest way to do this is by just using the implementation provided with this bundle. However you can of course provide your own implementation if you prefer to.

To use the implementation provided with this bundle use the following configuration:

```xml
<service id="acme_schedule_manager" class="Abc\Bundle\SchedulerBundle\Doctrine\ScheduleManager">
    <argument type="service" id="doctrine.orm.entity_manager"/>
    <argument>Acme\ScheduleBundle\Entity\Schedule</argument>
</service>
```

__Note:__ You need to replace `Acme\ScheduleBundle\Entity\Schedule` with the fully qualified class name of the entity class you created.

### Step 3: Register the schedule iterator

The next step is to register an iterator for the schedule manager. This will be used by the symfony command to iterate over all schedules and dispatch an event in case it is due. To so you only have to define another service and tag it.

```xml
<service id="acme_schedule_iterator" class="Abc\Bundle\SchedulerBundle\Iterator\ScheduleManagerScheduleIterator" public="true">
    <argument type="service" id="acme_schedule_manager"/>
    <tag name="abc.scheduler.iterator"/>
</service>
```

Please note that the `argument` specifies the entity manager that was registered in the previous step. The tag `abc.scheduler.iterator` is used to register this iterator within the AbcSchedulerBundle so that schedules will be continuously processed and an event gets notified when a schedule is due.

### Step 4: Define and register a listener

At this point everything is ready so that an event is dispatched if one of the schedules is due. However the listener is missing that will do something with it.

Whenever a schedule is due an event with the name `abc.schedule` of type `Abc\Bundle\SchedulerBundle\Event\SchedulerEvent` will be dispatched. To actually get notified if one of your schedules is due you need to create a listener class.

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

__Note:__ Please note the `instanceof` check in this example. It is important to add this in case you are working with different schedule entities or in case you are using third party dependencies that defines schedule entities.

This listener class finally needs to be registered within the service container.

```xml
<service id="acme_schedule_listener" class="Acme\ScheduleBundle\Listener\MyListener">
   <tag name="abc.scheduler.event_listener" method="onSchedule"/>
</service>
```

Please note that the method name specified in the attribute must match the name of the method in the listener class.

### Step 5: Setup the scheduler command

At this point everything should be working. The only missing part is invoking the scheduler command which will loop over all registered iterators and process their schedules. You can invoke this command manually like follows:

```shell
php app/console abc:scheduler:start
```

However this command cannot be used or started as it is in a production environment. You either need to setup a cron job that will invoke this command every minute or, and this is the recommended way, you setup a process control system like supervisord.

```shell
php app/console abc:scheduler:start --iteration=250
```

__Note:__ If you use a process control system like supervisord it is recommended to set an iteration limit. Reason for this is that memory consumption grows over the time and thus at some point you will get a `memory_limit_exceeded` sooner or later.

Next Step: [Basic Usage](./basic-usage.md)