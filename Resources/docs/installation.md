Installation
============

Download the bundle using composer:

```
$ composer require "aboutcoders/scheduler-bundle"
```

Include the bundle in the AppKernel.php class

```php
public function registerBundles()
{
    $bundles = array(
        new Abc\Bundle\SchedulerBundle\AbcSchedulerBundle(),
        // ...
    );
}
```

In case you want to install and configure the bundle only because you are using it as a third party dependency (for example if you use it with the [AbcJobBundle](https://github.com/aboutcoders/job-bundle)) you are done now. Otherwise if you want to define your own schedule entities please go on and read what needs to be done.

Next Step: [Associating Schedules With Custom Entities](./associating-schedules-with-custom-entities.md)