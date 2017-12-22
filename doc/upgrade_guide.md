---
currentMenu: upgrade_guide
---

## How to upgrade from MessageBus `1.x` to `2.0`

### The `Message`, `Command` and `Event` interfaces have been removed

A message (command, event, etc.) can be any object now, so just remove any type-hint or replace type-hints by more
specific ones.

The `Message` type-hint has been removed from the following methods:

- `SimpleBus\Message\Bus\MessageBus::handle`
- `SimpleBus\Message\Bus\Middleware\MessageBusMiddleware::handle`
- `SimpleBus\Message\Handler\Resolver\MessageHandlerResolver::resolve`
- `SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver::resolve`
- `SimpleBus\Message\Name\MessageNameResolver::resolve`

### The `MessageHandler` interface has been removed

Message handlers can be any callable now, so just remove the ` implements MessageHandler` from your message handler
class definitions.

### The `MessageSubscriber` interface has been removed

Message subscribers can be any callable now, so just remove the ` implements MessageSubscriber` from your message
handler class definitions.

### `MessageHandlerMap` and `MessageSubscriberCollection` have been removed

Instead, handlers and subscribers can now be any callable you like. Also, they are assumed to be always lazy-loading
(because they should never all be instantiated at the same time). See the updated documentation for examples on how to
configure these objects.
