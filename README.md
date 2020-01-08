# AbcSchedulerBundle

[![Build Status](https://travis-ci.org/aboutcoders/scheduler-bundle.png?branch=2.x)](https://travis-ci.org/aboutcoders/scheduler-bundle)

A Symfony bundle to process CRON based scheduling expressions.

**Note: This project is still in an experimental phase!**

## Getting Started

1. Define a schedule provider by implementing `ProviderInterface`.

	 ```php
	namespace Abc\Scheduler;
	
	interface ProviderInterface
	{
	    /**
	     * @return string The provider's name, used to bind a provider to processors
	     */
	    public function getName(): string;
	    
	    /**
	     * @param int|null $limit
	     * @param int|null $offset
	     * @return ScheduleInterface[]
	     */
	    public function provideSchedules(int $limit = null, int $offset = null): array;
	    
	    public function save(ScheduleInterface $schedule): void;
	}
    ```

2. Define a schedule processor by implementing `ProcessorInterface`.

	```php
	namespace Abc\Scheduler;
	
	/**
	 * Process a schedule that is due.
	 */
	interface ProcessorInterface
	{
	    public function process(ScheduleInterface $schedule);
	}
	```

3. Register the schedule provider with the tag `abc.scheduler.schedule_provider` and schedule processor with the tag `abc.scheduler.schedule_processor`.

	```yaml
	services:
	    App\Schedule\MyProvider:
	        tags:
	            - { name: 'abc.scheduler.schedule_provider' }
	
	    App\Schedule\MyProcessor:
	        tags:
	            - { name: 'abc.scheduler.schedule_processor' }
	```

4. Run the scheduler

	```bash
	bin/console abc:schedule
	```

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.
