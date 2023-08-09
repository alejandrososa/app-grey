<?php

namespace App\Core\Component\Infrastructure\Broker;

use App\Core\Shared\Domain\Utils;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class BrokerPass implements CompilerPassInterface
{
    public function __construct(private readonly Utils $utils)
    {
    }
    public function process(ContainerBuilder $containerBuilder): void
    {
        // always first check if the primary service is defined
        if (!$containerBuilder->has(MessageQueryProvider::class)) {
            return;
        }

        $definition = $containerBuilder->findDefinition(MessageQueryProvider::class);

        $this->registerRequestReplyMessageQuery($containerBuilder, $definition);
    }

    private function registerRequestReplyMessageQuery(ContainerBuilder $containerBuilder, Definition $definition): void
    {
        // find all service IDs with the app.mail_transport tag
        $taggedServices = $containerBuilder
            ->findTaggedServiceIds('app.core.components.broker.request_reply_message_query');

        foreach (array_keys($taggedServices) as $id) {
            $contextLinkName = $this->getContextLinkName($id);

            //set public service true
            $containerBuilder->findDefinition($id)->setPublic(true);

            //add message query to broker provider
            $definition->addMethodCall('addMessageQuery', [$contextLinkName, $id]);
        }
    }

    private function getContextLinkName(string $id): string
    {
        $utils = $this->utils;
        $contextAndSubDomain = $utils::extractContextAndSubDomain($id);
        $messageQuery = $utils::extractClassName($id);

        $prefixContext = $utils::toSnakeCase($contextAndSubDomain);
        $query = $utils::toSnakeCase($messageQuery);

        return sprintf('%s.%s', $prefixContext, $query);
    }
}
