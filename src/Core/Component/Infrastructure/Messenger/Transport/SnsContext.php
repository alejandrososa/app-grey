<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Transport;

use App\Core\Shared\Domain\Utils;
use Enqueue\Sns\SnsContext as BaseSnsContext;
use Enqueue\Sns\SnsMessage;
use Interop\Queue\Message;

class SnsContext extends BaseSnsContext
{
    public function createMessage(string $body = '', array $properties = [], array $headers = []): Message
    {
        $_body = Utils::jsonDecode($body);
        $subject = $_body['event_name'] ?? null;
        $snsMessage = new SnsMessage($body, $properties, $headers, subject: $subject);

        if (!empty($_body)) {
            $snsMessage->addAttribute('event', 'String', $subject);
            // foreach ($_body as $name => $value) {
            //    if ('meta' === $name) {
            //        continue;
            //    }
            //    $keyType = $this->getType($value);
            //    $value = is_array($value) ? array_values($value) : $value;
            //    $message->setAttribute($name, ['DataType' => $keyType, 'StringValue' => $value]);
            // }
        }

        return $snsMessage;
    }
}
