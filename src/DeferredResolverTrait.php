<?php

namespace Joskfg\GraphQLBulk;

use GraphQL\Deferred;
use Joskfg\GraphQLBulk\Interfaces\DeferredResolverInterface;

trait DeferredResolverTrait
{
    private static $buffer = [
        'roots'   => [],
        'results' => [],
    ];

    protected function deferredResolver(DeferredResolverInterface $resolver):\Closure
    {
        return function ($root, array $args = [], $context = null, $info = null) use ($resolver):Deferred {
            self::$buffer['roots'][] = $root;

            $buffer = &self::$buffer;

            return new Deferred(function () use (&$buffer, $resolver, $root, $args, $context, $info) {
                if (empty($buffer['results'])) {
                    $buffer['results'] = $resolver->fetch($buffer['roots'], $args, $context, $info);
                }

                return $resolver->pluck($root, $buffer['results']);
            });
        };
    }
}