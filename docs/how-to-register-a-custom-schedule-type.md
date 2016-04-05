How-to define a custom schedule type
====================================

You can easily define your own schedule types by simply writing the processor and registering it in the service container.

First you need to create a class that implements the interface `Abc\Bundle\SchedulerBundle\Schedule\ProcessorInterface`.

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

Next you have to register this new processor in the service container and tag it like this:

```xml
<service id="acme_custom_processor" class="Acme\ScheduleBundle\Processor\MyProcessor">
   <tag name="abc.scheduler.processor" type="mytype"/>
</service>
```

You can now add you custom schedules like this:

```php
$schedule = $manager->create();
$schedule->setType('mytype');
$schedule->setExpression($myExpression);
$manager->update($schedule);
```