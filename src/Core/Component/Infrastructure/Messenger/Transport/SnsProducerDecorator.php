<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Transport;

use Enqueue\Sns\SnsMessage;
use Interop\Queue\Destination;
use Interop\Queue\Message;
use Interop\Queue\Producer;

class SnsProducerDecorator implements Producer
{
    public function __construct(private Producer $producer)
    {
    }

    public function send(Destination $destination, Message $message): void
    {
        /* @var SnsMessage $message */
        $message->setAttribute('demo', ['StringValue' => 'demo']);
        $message->setSubject('event.demo');
        $this->producer->send($destination, $message);
    }

    public function setDeliveryDelay(int $deliveryDelay = null): Producer
    {
        return $this->producer->setDeliveryDelay($deliveryDelay);
    }

    public function getDeliveryDelay(): ?int
    {
        return $this->producer->getDeliveryDelay();
    }

    public function setPriority(int $priority = null): Producer
    {
        return $this->producer->setPriority($priority);
    }

    public function getPriority(): ?int
    {
        return $this->producer->getPriority();
    }

    public function setTimeToLive(int $timeToLive = null): Producer
    {
        return $this->producer->setTimeToLive($timeToLive);
    }

    public function getTimeToLive(): ?int
    {
        return $this->producer->getTimeToLive();
    }
}
