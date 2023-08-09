<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Transport;

use App\Core\Component\Infrastructure\Bus\Event\Enqueue\Transformer\EnqueueDomainEventDecode;
use App\Core\Component\Infrastructure\Bus\Event\Enqueue\Transformer\EnqueueDomainEventEncode;
use App\Core\Shared\Domain\Utils;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class SqsSnsSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $stamps = $this->getStamps($encodedEnvelope['body']);
        $message = (new EnqueueDomainEventDecode())->write($encodedEnvelope)->read();

        return new Envelope($message, $stamps);
    }

    public function encode(Envelope $envelope): array
    {
        return (new EnqueueDomainEventEncode())->write($envelope)->read();
    }

    private function getStamps($json): mixed
    {
        $body = Utils::jsonDecode($json);
        $messageBody = empty($body['Message'])
            ? $body
            : Utils::jsonDecode($body['Message']);

        $stamps = [];
        if (isset($messageBody['meta']['stamps'])) {
            $stamps = unserialize(stripcslashes($messageBody['meta']['stamps']));
        }

        return $stamps;
    }
}
