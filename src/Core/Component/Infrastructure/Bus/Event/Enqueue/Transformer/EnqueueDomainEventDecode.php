<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue\Transformer;

use App\Core\Shared\Domain\Bus\Event\Event;
use App\Core\Shared\Domain\Bus\Event\EventReceived;
use App\Core\Shared\Domain\Bus\Event\EventTransformer;
use App\Core\Shared\Domain\Bus\Event\MessageDomainEvent;
use App\Core\Shared\Domain\Bus\Event\UnknownMessageWasReceived;
use App\Core\Shared\Domain\Utils;
use ReflectionException;

class EnqueueDomainEventDecode implements EventTransformer
{

    /** @param array<string, object>|null $encodedEnvelope */
    public function __construct(private ?array $encodedEnvelope)
    {
    }

    public function write($data): EventTransformer
    {
        $this->encodedEnvelope = $data;

        return $this;
    }

    public function read(): EventReceived|Event
    {
        if (empty($this->encodedEnvelope['body'])) {
            return new UnknownMessageWasReceived($this->encodedEnvelope['body']);
        }

        $body = Utils::jsonDecode($this->encodedEnvelope['body']);
        if (empty($body['Message'])) {
            return new UnknownMessageWasReceived($this->encodedEnvelope);
        }

        $messageBody = Utils::jsonDecode($body['Message']);
        if (empty($body['event_name']) && empty($messageBody['event_name'])) {
            return new UnknownMessageWasReceived($this->encodedEnvelope['body']);
        }

        return $this->parseData($messageBody);
    }

    /**
     * @throws ReflectionException
     */
    private function parseData(array $body): ?EventReceived
    {
        if (empty($body['event_name']) || empty($body['attributes'])) {
            return null;
        }

        return MessageDomainEvent::fromPrimitives(
            $body['aggregate_id'],
            $body['attributes'],
            $body['event_id'],
            $body['event_name'],
            $body['occurred_on']
        );
    }
}
