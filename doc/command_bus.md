---
currentMenu: command_bus
---

# Implementing a command bus

The classes and interfaces from this package can be used to set up a command bus. The characteristics of a command bus
are:

- It handles *commands*, i.e. imperative messages
- Commands are handled by exactly one *command handler*
- The behavior of the command bus is extensible: *middlewares* are allowed to do things before or after handling a
command

## Setting up the command bus

At least we need an instance of `MessageBusSupportingMiddleware`:

```php
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

$commandBus = new MessageBusSupportingMiddleware();
```

### Finish handling a command, before handling the next

We want to make sure that commands are always fully handled before other commands will be handled, so we add a
specialized middleware for that:

```php
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;

$commandBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
```

### Defining the command handler map {#command-handler-map}

Now we also want commands to be handled by exactly one command handler. We first need to define the collection of
handlers that are available in the application. We should make this *command handler map* lazy-loading, or every
command handler will be fully loaded, even though it is not going to be used:

```php
use SimpleBus\Message\Handler\Map\LazyLoadingMessageHandlerMap;

// provide a service locator callable
$serviceLocator = function ($serviceId) {
    // lazily load/create an instance of the command handler, e.g. using an IoC container
    $handler = ...;

    return $handler;
}

// provide a map of command names to service ids
$commandHandlersByCommandName = [
    'Fully\Qualified\Class\Name\Of\Command' => 'command_handler_service_id'
];

$commandHandlerMap = new LazyLoadingMessageHandlerMap(
    $commandHandlersByCommandName,
    $serviceLocator
);
```

### Resolving the command handler for a command

#### The name of a command

First we need a way to resolve the name of a command. You can use the fully-qualified class name (FQCN) of a
command object as its name:

```php
use SimpleBus\Message\Name\ClassBasedNameResolver;

$commandNameResolver = new ClassBasedNameResolver();
```

Or you can ask command objects what their name is:

```php
use SimpleBus\Message\Name\NamedMessageNameResolver;

$commandNameResolver = new NamedMessageNameResolver();
```

In that case your commands have to implement `NamedMessage`:

```php
use SimpleBus\Message\Name\NamedMessage;

class YourCommand implements NamedMessage
{
    public static function messageName()
    {
        return 'your_command';
    }
}
```

> #### Implementing your own `MessageNameResolver`
>
> If you want to use another rule to determine the name of a command, create a class that implements
> `SimpleBus\Message\Name\MessageNameResolver`.

### Resolving the command handler based on the name of the command

Using the `MessageNameResolver` of your choice, you can now let the *command handler resolver* find the right command
handler for a given command.

```php
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;

$commandHandlerResolver = new NameBasedMessageHandlerResolver(
    $commandNameResolver,
    $commandHandlerMap
);
```

Finally, we should add some middleware to the command bus that calls the resolved command handler:

```php
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;

$commandBus->appendMiddleware(
    new DelegatesToMessageHandlerMiddleware(
        $commandHandlerResolver
    )
);
```

## Using the command bus: an example

Consider the following command:

```php
class RegisterUser
{
    private $emailAddress;
    private $plainTextPassword;

    public function __construct($emailAddress, $plainTextPassword)
    {
        $this->emailAddress = $emailAddress;
        $this->plainTextPassword = $plainTextPassword;
    }

    public function emailAddress()
    {
        return $this->emailAddress;
    }

    public function plainTextPassword()
    {
        return $this->plainTextPassword;
    }
}
```

This command communicates the intention to "register a new user". The message data consists of an email address and a
password in plain text. This information is required to execute the desired behavior.

The handler for this command looks like this:

```php
use SimpleBus\Message\Handler\MessageHandler;

class RegisterUserCommandHandler implements MessageHandler
{
    ...

    public function handle($message)
    {
        $user = User::register(
            $message->emailAddress(),
            $message->plainTextPassword()
        );

        $this->userRepository->add($user);
    }
}
```

We should register this handler as a service and add the service id to the [command handler map](#command-handler-map).
Since we have already fully configured the command bus, we can just start creating a new command object and let the
command bus handle it. Eventually the command will be passed as a message to the `RegisterUserCommandHandler`:

```php
$command = new RegisterUser(
    'matthiasnoback@gmail.com',
    's3cr3t'
);

$commandBus->handle($command);
```

> #### Implementing your own command bus middleware
>
> It's very easy to extend the behavior of the command bus. You can create a class that implements
> `MessageBusMiddleware`:
>
> ```php
> use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
>
> /**
>  * Marker interface for commands that should be handled asynchronously
>  */
> interface IsHandledAsynchronously
> {
> }
>
> class HandleCommandsAsynchronously implements MessageBusMiddleware
> {
>     ...
>
>     public function handle($message, callable $next)
>     {
>         if ($message instanceof IsHandledAsynchronously) {
>             // handle the message asynchronously using a message queue
>             $this->messageQueue->add($message);
>         } else {
>             // handle the message synchronously, i.e. right-away
>             $next($message);
>         }
>     }
> }
> ```
>
> You should add an instance of that class as middleware to any `MessageBusSupportingMiddleware` instance (like the
> command bus we created earlier):
>
> ```php
> $commandBus->appendMiddleware(new HandleCommandsAsynchronously());
> ```
>
> Make sure that you do this at the right place, before or after you add the other middlewares.
>
> Calling `$next($message)` will make sure that the next middleware in line is able to handle the message.

> #### Logging messages
>
> To log every message that passes through the command bus, add the `LoggingMiddleware` right before the
> `DelegatesToMessageHandlerMiddleware`. Make sure to set up a [PSR-3 compliant
> logger](http://www.php-fig.org/psr/psr-3/) first:
>
> ```php
> use Psr\Log\LoggerInterface;
> use Psr\Log\LogLevel;
>
> // $logger is an instance of LoggerInterface
> $logger = ...;
> $loggingMiddleware = new LoggingMiddleware($logger, LogLevel::DEBUG);
> $commandBus->appendMiddleware($loggingMiddleware);
> ```

Continue to read about the perfect complement to the command bus: the [event bus](event_bus.md).
