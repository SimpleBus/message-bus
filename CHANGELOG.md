# Change log

## [Unreleased][unreleased]

### Added

- Added a change log.

## [1.0.2] - 20-01-2015

- When an exception occurs in a message handler/subscriber, recorded messages (e.g. events) will now be erased.

## [1.0.1] - 20-01-2015

### Changed

- When an exception occurs in a message handler/subscriber, the command bus will no longer be locked.

## 1.0.0 - 19-01-2015

### Added

- Import of classes and interfaces from SimpleBus/CommandBus and SimpleBus/EventBus 1.0
- This library applies generically to all kinds of messages.

[unreleased]: https://github.com/SimpleBus/MessageBus/compare/v1.0.1...HEAD
[1.0.2]: https://github.com/SimpleBus/MessageBus/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/SimpleBus/MessageBus/compare/v1.0.0...v1.0.1
