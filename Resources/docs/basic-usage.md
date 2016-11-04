Basic Usage
===========

## Creating a schedule

A schedule is defined by its type and expression each defined as string. At the current point there are two types supported: `cron` and `timestamp`.

Use your previously registered schedule manager class to create a new schedule that will be executed every minute:

```php
$manager = $this->get('acme_schedule_manager');

$schedule = $manager->create();
$schedule->setType('cron');
$schedule->setExpression('* * * * *');
$manager->update($schedule);
```

## CRON Schedules

Schedules of type `cron` are defined by CRON expressions as known in LINUX bases systems. The bundle relies on the [PHP Cron Expression Parser](https://github.com/mtdowling/cron-expression/) library to parse these expressions. The parts of a CRON schedule are as follows:

    *    *    *    *    *    *
    -    -    -    -    -    -
    |    |    |    |    |    |
    |    |    |    |    |    + year [optional]
    |    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
    |    |    |    +---------- month (1 - 12)
    |    |    +--------------- day of month (1 - 31)
    |    +-------------------- hour (0 - 23)
    +------------------------- min (0 - 59)

## Timestamp Schedules

Schedules of type `timestamp` are executed only once. The expression for it must be a UNIX timestamp value.

```php
$manager = $this->get('acme_schedule_manager');

$schedule = $manager->create();
$schedule->setType('datetime');
$schedule->setExpression(new DateTime('2016-01-01 00:00:01'));
$manager->update($schedule);
```

Back to [index](../../README.md)