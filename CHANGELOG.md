# Change log

## [2.0.2]

- When an exception occurs in a message handler/subscriber, recorded messages (e.g. events) will now be erased.

## [2.0.1]

### Changed

- When an exception occurs in a message handler/subscriber, the command bus will no longer be locked.

## [2.0.0]

### Added

- Import of classes and interfaces from SimpleBus/CommandBus and SimpleBus/EventBus 1.0
- This library applies generically to all kinds of messages.
