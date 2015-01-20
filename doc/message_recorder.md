---
currentMenu: message_recorder
---

# Recording events and handling them {#recording-events}

While the [command bus](#command-bus) handles a command, certain events will take place. It might be important to
*record these events* and, when the command has been fully handled, notify other parts of the system about the events
that were recorded.

This can be accomplished by using *message recorders*. These are objects with the ability to record messages. From the
outside these messages can be retrieved, and erased:

```php
interface ContainsRecordedMessages
{
    public function recordedMessages();

    public function eraseMessages();
}
```

## Collecting events

### Publicly {#publicly-recorded-messages}

The default implementation, which has a public `record()` method as well, is the `PublicMessageRecorder`:

```php
use SimpleBus\Message\Recorder\PublicMessageRecorder;

$publicMessageRecorder = new PublicMessageRecorder();

$event = new UserRegistered(...);

$publicMessageRecorder->record($event);

$recordedEvents = $publicMessageRecorder->recordedEvents();
// $recordedEvents is an array containing the previously recorded $event object
```

### Privately

When you use domain events, your domain entities will generate events while you change them. You record those events
inside the entity. Later, when the changes have been persisted and the database transaction has succeeded, you should
collect the recorded events and handle them:

```php
$entity->changeSomething();
// $entity generates a SomethingChanged event and records it internally

// start transaction
$entityManager->persist($entity);
// commit transaction

$events = $entity->recordedEvents();

// handle the events
foreach ($events as $event) {
    $eventBus->handle($event);
}
```

You can give your entities the ability to record their own events by letting them implement the `RecordsMessages`
interface and using the `PrivateMessageRecorderCapabilities` trait:

```php
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;

class YourEntity implements RecordsMessages
{
    use PrivateMessageRecorderCapabilities;

    public function changeSomething()
    {
        ...

        $this->record(new SomethingChanged());
    }
}
```

## Handling events

### Handling publicly recorded events

Events are recorded while a command is handled. We only want to handle the events themselves *after* the command has
been completely and successfully been handled. The best option to accomplish this is to add a piece of middleware to the
command bus. This middleware needs the *message recorder* to find out which events were recorded during the
handling of a command, and it needs the *event bus* to actually handle the recorded events:

```php
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;

$commandBus->appendMiddleware(new HandlesRecordedMessagesMiddleware(
    $publicMessageRecorder,
    $eventBus
));
```

Make sure to add this middleware *first*, before adding any other middleware. Like mentioned before: we only want events
to be handled when we know that everything else has gone well.

## Handling domain events

When you privately record events inside your domain entities, you need to collect those recorded events manually. Your
database abstraction library, ORM or ODM probably offers a way to hook into the process of persisting the entities and
collecting them somehow. After the command has been handled successfully and the transaction has been committed,
you can iterate over those entities and collect their recorded events.

> #### Handling domain events with Doctrine ORM
>
> SimpleBus comes with a [Doctrine ORM bridge](https://github.com/SimpleBus/DoctrineORMBridge). Using this package you
> can collect recorded events from Doctrine ORM entities. See its
> [README](https://github.com/SimpleBus/DoctrineORMBridge/blob/master/README.md) file for further instructions.

## Combining multiple message recorders

If you have multiple ways in which you record events, e.g. using the `PublicMessageRecorder` and using domain events,
you can combine those into one message recorder, which aggregates the recorded messages:

```php
use SimpleBus\Message\Recorder\AggregatesRecordedMessages;

$aggregatingMessageRecorder(
    [
        $publicMessageRecorder,
        $domainEventsMessagesRecorder,
        ...
    ]
)
```

Finally, you can provide this aggregating message recorder to the `HandlesRecordedMessagesMiddleware` and it will act as
if it is a single message recorder.

```php
$commandBus->appendMiddleware(new HandlesRecordedMessagesMiddleware(
    $aggregatingMessageRecorder,
    $eventBus
));
```
