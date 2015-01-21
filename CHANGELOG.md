# Change log

## [Unreleased][unreleased]

### Added

- Added a change log.

## [2.0.2]

- When an exception occurs in a message handler/subscriber, recorded messages (e.g. events) will now be erased.

## [2.0.1]

### Changed

- When an exception occurs in a message handler/subscriber, the command bus will no longer be locked.

## [2.0.0]

### Added

- Import of classes and interfaces from SimpleBus/CommandBus and SimpleBus/EventBus 1.0
- This library applies generically to all kinds of messages.

[unreleased]: https://github.com/simple-bus/message-bus/compare/v2.0.1...HEAD
[2.0.2]: https://github.com/simple-bus/message-bus/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/simple-bus/message-bus/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/simple-bus/message-bus/compare/v1.0.0...v2.0.0
