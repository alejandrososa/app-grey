<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Middleware;

use Psr\Log\NullLogger;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Envelope;
use App\Core\Shared\Domain\Bus\Event\EventReceived;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use App\Core\Shared\Domain\Bus\Event\MessageDomainEvent;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use App\Core\Component\Infrastructure\Bus\Event\DomainEventSubscriberLocator;

use function Lambdish\Phunctional\map;

class HandleMessageDomainEventMiddleware implements MiddlewareInterface
{
    use LoggerAwareTrait;

    private const IS_EVENT_DOMAIN_RECEIVED = true;

    private HandlersLocatorInterface $handlersLocator;
    private bool $allowNoHandlers;
    private DomainEventSubscriberLocator $domainEventSubscriberLocator;

    public function __construct(
        HandlersLocatorInterface $handlersLocator,
        DomainEventSubscriberLocator $domainEventSubscriberLocator,
        bool $allowNoHandlers = true
    ) {
        $this->handlersLocator = $handlersLocator;
        $this->allowNoHandlers = $allowNoHandlers;
        $this->logger = new NullLogger();
        $this->domainEventSubscriberLocator = $domainEventSubscriberLocator;
    }

    /**
     * @throws NoHandlerForMessageException When no handler is found and $allowNoHandlers is false
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        // message type event received
        if ($message instanceof MessageDomainEvent) {
            $handlers = $this->domainEventSubscriberLocator->allSubscribedTo($message->eventName());
            $handlerDescriptors = map($this->classDescriptorExtractor(), $handlers);

            return $this->handleEnvelop($envelope, $handlerDescriptors, self::IS_EVENT_DOMAIN_RECEIVED);
        }

        // other messages
        $handlerDescriptors = $this->handlersLocator->getHandlers($envelope);
        $envelope = $this->handleEnvelop($envelope, $handlerDescriptors);

        return $stack->next()->handle($envelope, $stack);
    }

    private function classDescriptorExtractor(): \Closure
    {
        return fn (callable $handler): HandlerDescriptor => new HandlerDescriptor($handler);
    }

    private function messageHasAlreadyBeenHandled(Envelope $envelope, HandlerDescriptor $handlerDescriptor): bool
    {
        $stamps = $envelope->all(HandledStamp::class);

        $some = array_filter(
            $stamps,
            fn (StampInterface $stamp): bool => (
                $stamp instanceof HandledStamp
                && $stamp->getHandlerName() === $handlerDescriptor->getName()
            )
        );

        return $some !== [];
    }

    /**
     * @param iterable<HandlerDescriptor> $handlers
     */
    private function handleEnvelop(Envelope $envelope, iterable $handlers, bool $isDomainEvent = false): Envelope
    {
        /** @var EventReceived $message */
        $message = $envelope->getMessage();

        $handler = null;
        $exceptions = [];
        $context = [
            'message' => $message,
            'class' => $isDomainEvent ? $message->eventName() : $message::class,
        ];

        $noHandlerForMessageText = $isDomainEvent
            ? 'No handler for domain event message'
            : 'No handler for message';

        $handlerForMessageText = $isDomainEvent ? 'Domain event message' : 'Message----';

        foreach ($handlers as $handlerDescriptor) {
            if ($this->messageHasAlreadyBeenHandled($envelope, $handlerDescriptor)) {
                continue;
            }

            try {
                $handler = $handlerDescriptor->getHandler();
                $handledStamp = HandledStamp::fromDescriptor($handlerDescriptor, $handler($message));
                $envelope = $envelope->with($handledStamp);
                $this->logger->info(
                    $handlerForMessageText . ' {class} handled by {handler}',
                    $context + ['handler' => $handledStamp->getHandlerName()]
                );
            } catch (\Throwable $e) {
                $exceptions[] = $e;
            }
        }

        if ($handler === null) {
            if (!$this->allowNoHandlers) {
                $exceptionMessage = sprintf('%s "%s".', $noHandlerForMessageText, $context['class']);

                throw new NoHandlerForMessageException($exceptionMessage);
            }

            $this->logger->info($noHandlerForMessageText . ' {class}', $context);
        }

        if ($exceptions !== []) {
            throw new HandlerFailedException($envelope, $exceptions);
        }

        return $envelope;
    }
}
