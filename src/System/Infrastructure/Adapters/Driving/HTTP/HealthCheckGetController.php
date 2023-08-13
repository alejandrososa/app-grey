<?php

declare(strict_types=1);

namespace App\System\Infrastructure\Adapters\Driving\HTTP;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class HealthCheckGetController
{
    #[Route('/health-check', name: 'system.health-check_get', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            [
                'system' => 'ok',
            ]
        );
    }
}
