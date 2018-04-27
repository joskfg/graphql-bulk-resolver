<?php

namespace Joskfg\GraphQLBulk;

use GraphQL\Deferred;
use Joskfg\GraphQLBulk\Interfaces\DeferredResolverInterface;

trait DeferredResolverTrait
{
    private static $buffer = [];

    private function deferredResolver(DeferredResolverInterface $resolver): \Closure
    {
        return function ($root, array $args = [], $context = null, $info = null) use ($resolver): Deferred {
            $resolverName = get_class($resolver);
            self::$buffer[$resolverName] ?? self::$buffer[$resolverName] = [];

            $buffer            = &self::$buffer[$resolverName];
            $buffer['results'] = [];
            $buffer['roots'][] = $root;

            return new Deferred(function () use (&$buffer, $resolver, $root, $args, $context, $info) {
                if (empty($buffer['results'])) {
                    $buffer['results'] = $resolver->fetch($buffer['roots'], $args, $context, $info);
                    $buffer['roots']   = [];
                }

                return $resolver->pluck($root, $buffer['results']);
            });
        };
    }
}