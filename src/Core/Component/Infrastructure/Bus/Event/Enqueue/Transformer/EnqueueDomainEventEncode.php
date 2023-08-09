<?php

namespace App\Core\Component\Infrastructure\Bus\Event\Enqueue\Transformer;

use App\Core\Shared\Domain\Bus\Event\Event;
use App\Core\Shared\Domain\Bus\Event\EventReceived;
use App\Core\Shared\Domain\Bus\Event\EventTransformer;
use App\Core\Shared\Domain\Utils;
use Symfony\Component\Messenger\Envelope;

class EnqueueDomainEventEncode implements EventTransformer
{
    private ?Envelope $envelope;

    public function write($data): EventTransformer
    {
        $this->envelope = $data;

        return $this;
    }

    public function read(): EventReceived|Event
    {
        if (!$this->envelope->getMessage() instanceof Event) {
            return [];
        }

        $allStamps = [];
        foreach ($this->envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, (array) $stamps);
        }

        /** @var Event $message */
        $message = $this->envelope->getMessage();

        return [
            'body' => Utils::jsonEncode([
                'event_name' => $message->eventName(),
                'event_id' => $message->eventId(),
                'occurred_on' => $message->occurredOn(),
                'aggregate_id' => $message->aggregateId(),
                'attributes' => $message->toPrimitives(),
                'meta' => [
                    'type' => \get_class($message),
                    'stamps' => addslashes(serialize($allStamps)),
                ],
            ]),
            //            'properties' => [
            //                'event' => $message->eventName(),
            //            ],
            //            'headers' => [],
        ];
    }
}
