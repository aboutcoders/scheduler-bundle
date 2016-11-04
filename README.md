AbcSchedulerBundle
==================

A symfony bundle that allows you define schedules for recurring events which will be notified using the [Symfony Event Dispatcher](http://symfony.com/doc/current/components/event_dispatcher/index.html).

This bundle cannot be used *out of the box* but requires that you define your own schedule entities. Please take a look at the [AbcJobBundle](https://github.com/aboutcoders/job-bundle) to see a concrete usage of this bundle.

Build Status: [![Build Status](https://travis-ci.org/aboutcoders/scheduler-bundle.svg?branch=master)](https://travis-ci.org/aboutcoders/scheduler-bundle)

## Documentation

- [Installation](./Resources/docs/installation.md)
- [Associating Schedules With Custom Entities](./Resources/docs/associating-schedules-with-custom-entities.md)
- [Basic Usage](./Resources/docs/basic-usage.md)
- [Configuration Reference](./Resources/docs/configuration-reference.md)

## How-tos

- [How-to register a custom schedule type](./how-to-register-a-custom-schedule-type.md)

### Planned Features

- Provide factories/builders to ease schedule creation
- Add option to enable/disable a schedule

## License

The MIT License (MIT). Please see [License File](./LICENSE) for more information.