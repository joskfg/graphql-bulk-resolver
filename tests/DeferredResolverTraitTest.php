<?php declare(strict_types=1);
/** @noinspection PhpUndefinedMethodInspection */

namespace Joskfg\GraphQLBulk;

use GraphQL\Deferred;
use GraphQL\Executor\Promise\Adapter\SyncPromise;
use Joskfg\GraphQLBulk\Interfaces\DeferredResolverInterface;
use PHPUnit\Framework\TestCase;

class DeferredResolverTraitTest extends TestCase
{
    /**
     * @test
     */
    public function whenReceiveAnObjectItShouldCallFetchAndPluckTransmittingTheInformation()
    {
        $resolver         = \Mockery::mock(DeferredResolverInterface::class);
        $deferredResolver = $this->getResolver();

        $resolver->shouldReceive('fetch')
            ->with(
                ['root1', 'root2', 'root3'],
                ['args'],
                'context',
                'info'
            )
            ->andReturn(['result1', 'result2', 'result3']);

        $resolver->shouldReceive('pluck')
            ->with(
                'root1',
                ['result1', 'result2', 'result3']
            )
            ->andReturn('result1');
        $resolver->shouldReceive('pluck')
            ->with(
                'root2',
                ['result1', 'result2', 'result3']
            )
            ->andReturn('result2');
        $resolver->shouldReceive('pluck')
            ->with(
                'root3',
                ['result1', 'result2', 'result3']
            )
            ->andReturn('result3');

        $resolveField = $deferredResolver->run($resolver);

        $firstPromise  = $resolveField('root1', ['args'], 'context', 'info');
        $secondPromise = $resolveField('root2', ['args'], 'context', 'info');
        $thirdPromise  = $resolveField('root3', ['args'], 'context', 'info');

        $firstResult  = null;
        $secondResult = null;
        $thirdResult  = null;

        $firstPromise->then(function ($result) use (&$firstResult) {
            $firstResult = $result;
        });
        $secondPromise->then(function ($result) use (&$secondResult) {
            $secondResult = $result;
        });
        $thirdPromise->then(function ($result) use (&$thirdResult) {
            $thirdResult = $result;
        });

        $this->resolvePromises();

        $this->assertEquals('result1', $firstResult);
        $this->assertEquals('result2', $secondResult);
        $this->assertEquals('result3', $thirdResult);
    }

    /**
     * @test
     */
    public function whenReceiveNewCallsAfterFetchItShouldReturnJustTheNewData()
    {
        $resolver         = \Mockery::mock(DeferredResolverInterface::class);
        $deferredResolver = $this->getResolver();

        $resolver->shouldReceive('fetch')
            ->with(
                ['root1'],
                ['args'],
                'context',
                'info'
            )
            ->andReturn(['result1']);
        $resolver->shouldReceive('pluck')
            ->with(
                'root1',
                ['result1']
            )
            ->andReturn('result1');

        $resolver->shouldReceive('fetch')
            ->with(
                ['root2'],
                ['args'],
                'context',
                'info'
            )
            ->andReturn(['result2']);
        $resolver->shouldReceive('pluck')
            ->with(
                'root2',
                ['result2']
            )
            ->andReturn('result2');

        $resolveField = $deferredResolver->run($resolver);

        $firstPromise = $resolveField('root1', ['args'], 'context', 'info');
        $firstResult  = null;
        $firstPromise->then(function ($result) use (&$firstResult) {
            $firstResult = $result;
        });

        $this->resolvePromises();
        $this->assertEquals('result1', $firstResult);

        $secondPromise = $resolveField('root2', ['args'], 'context', 'info');
        $secondResult  = null;
        $secondPromise->then(function ($result) use (&$secondResult) {
            $secondResult = $result;
        });

        $this->resolvePromises();
        $this->assertEquals('result2', $secondResult);
    }

    private function getResolver()
    {
        return new class() {
            use DeferredResolverTrait;

            public function run($resolver)
            {
                return $this->deferredResolver($resolver);
            }
        };
    }

    private function resolvePromises()
    {
        Deferred::runQueue();
        SyncPromise::runQueue();
    }
}
