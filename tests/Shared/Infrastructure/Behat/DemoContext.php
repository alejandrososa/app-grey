<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class DemoContext implements Context
{
    private \Symfony\Component\HttpKernel\KernelInterface $kernel;

    private ?\Symfony\Component\HttpFoundation\Response $response = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path, \Symfony\Component\HttpFoundation\Request::METHOD_GET));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (!$this->response instanceof \Symfony\Component\HttpFoundation\Response) {
            throw new \RuntimeException('No response received');
        }
    }
}
