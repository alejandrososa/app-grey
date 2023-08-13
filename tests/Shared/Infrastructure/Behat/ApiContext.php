<?php

declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Behat;

use Behat\Mink\Session;
use Behat\Gherkin\Node\PyStringNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Tests\Shared\Infrastructure\Mink\MinkHelper;
use App\Tests\Shared\Infrastructure\Mink\MinkSessionRequestHelper;

final class ApiContext extends RawMinkContext
{
    private MinkHelper $minkHelper;
    private MinkSessionRequestHelper $minkSessionRequestHelper;

    public function __construct(private Session $minkSession, KernelInterface $kernel)
    {
        $this->minkHelper = new MinkHelper($this->minkSession);
        $this->minkSessionRequestHelper = new MinkSessionRequestHelper(new MinkHelper($minkSession));
    }

    /**
     * @Given I send a :method request to :url
     */
    public function iSendARequestTo($method, $url): void
    {
        $this->minkSessionRequestHelper->sendRequest($method, $this->locatePath($url));
    }

    /**
     * @Given I send a :method request to :url with body:
     */
    public function iSendARequestToWithBody($method, $url, PyStringNode $pyStringNode): void
    {
        $this->minkSessionRequestHelper->sendRequestWithPyStringNode($method, $this->locatePath($url), $pyStringNode);
    }

    /**
     * @Then the response content should be:
     */
    public function theResponseContentShouldBe(PyStringNode $pyStringNode): void
    {
        $expected = $this->sanitizeOutput($pyStringNode->getRaw());
        $actual = $this->sanitizeOutput($this->minkHelper->getResponse());

        if ($expected !== $actual) {
            throw new \RuntimeException(sprintf("The outputs does not match!\n\n-- Expected:\n%s\n\n-- Actual:\n%s", $expected, $actual));
        }
    }

    /**
     * @Then the response should be empty
     */
    public function theResponseShouldBeEmpty(): void
    {
        $actual = trim($this->minkHelper->getResponse());

        if ($actual !== '') {
            throw new \RuntimeException(sprintf("The outputs is not empty, Actual:\n%s", $actual));
        }
    }

    /**
     * @Then print last api response
     */
    public function printApiResponse(): void
    {
        echo $this->minkHelper->getResponse();
    }

    /**
     * @Then print response headers
     */
    public function printResponseHeaders(): void
    {
        echo $this->minkHelper->getResponseHeaders();
    }

    /**
     * @Then the response status code should be :expectedResponseCode
     */
    public function theResponseStatusCodeShouldBe($expectedResponseCode): void
    {
        if ($this->minkSession->getStatusCode() !== (int)$expectedResponseCode) {
            throw new \RuntimeException(sprintf('The status code <%s> does not match the expected <%s>', $this->minkSession->getStatusCode(), $expectedResponseCode));
        }
    }

    private function sanitizeOutput(string $output): false|string
    {
        return json_encode(json_decode(trim($output), true));
    }
}
