GraphQL Bulk Resolver
=====

[![Latest Version](https://img.shields.io/github/release/joskfg/graphql-bulk-resolver.svg?style=flat-square)](https://github.com/joskfg/graphql-bulk-resolver/releases)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/joskfg/graphql-bulk-resolver/master.svg?style=flat-square)](https://travis-ci.org/joskfg/graphql-bulk-resolver)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/joskfg/graphql-bulk-resolver.svg?style=flat-square)](https://scrutinizer-ci.com/g/joskfg/graphql-bulk-resolver/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/joskfg/graphql-bulk-resolver.svg?style=flat-square)](https://scrutinizer-ci.com/g/joskfg/graphql-bulk-resolver)
[![Total Downloads](https://img.shields.io/packagist/dt/joskfg/graphql-bulk-resolver.svg?style=flat-square)](https://packagist.org/packages/joskfg/graphql-bulk-resolver)

Trait to avoid [N+1 problem](http://webonyx.github.io/graphql-php/data-fetching/#solving-n1-problem) in GraphQL when using the [webonyx/graphql-php](https://github.com/webonyx/graphql-php) or any wrapper.
It is based in the Deferred resolvers that provide the library and allow you to work with them in an easy way.

Documentation
-------

The trait split the resolver in two parts, fetch and pluck.

### Fetch
This part receive all the root objects in one array, so you can process all of them in one shoot and return all the data in the
best way to be processed in the pluck.

### Pluck
The method receives all the data processed and the root that is requesting the info, so it should search inside the data processed
and return the specific data.

You must implement the resolver using the `DeferredResolverInterface` and use the trait in the resolver type like this:
```
'resolve' => $this->deferredResolve(new MyResolver());
```

You can see an article about the package at https://medium.com/@JoseCardona/solving-graphql-n-1-in-php-92ed9161dd7b

Testing
-------

`joskfg/graphql-bulk-resolver` has a [PHPUnit](https://phpunit.de) test suite and a coding style compliance test suite using [PHP CS Fixer](http://cs.sensiolabs.org/).

To run the tests, run the following command from the project folder.

``` bash
$ docker-compose run test
```

To run interactively using [PsySH](http://psysh.org/):
``` bash
$ docker-compose run psysh
```

License
-------

The MIT license. Please see [LICENSE](LICENSE.md) for more information.

[PSR-2]: http://www.php-fig.org/psr/psr-2/
[PSR-4]: http://www.php-fig.org/psr/psr-4/
