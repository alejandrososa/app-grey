<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

final class ViolationException extends \Exception implements ViolationExceptionInterface
{
    /**
     * @var array<ViolationInterface>
     */
    private array $violations;

    /**
     * @param array<ViolationInterface> $violations
     */
    public function __construct(array $violations, string $message = '', int $code = 0, \Throwable $throwable = null)
    {
        $violation = \reset($violations);
        if (!$violation instanceof ViolationInterface) {
            throw new \InvalidArgumentException('Expected nonempty array of ViolationInterface instances.');
        }
        $this->violations = $violations;
        parent::__construct($message ?: $violation->getMessage(), $code, $throwable);
    }

    public static function for(ViolationInterface ...$violation): self
    {
        return new self($violation);
    }

    /**
     * @return array<ViolationInterface>
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
