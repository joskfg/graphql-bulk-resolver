<?php
/**
 * Created by PhpStorm.
 * User: jose
 * Date: 9/03/18
 * Time: 17:58
 */

namespace Joskfg\GraphQLBulk\Interfaces;


interface DeferredResolverInterface
{
    /**
     * Process all roots and return all the information obtained.
     *
     * @param array $roots
     *
     * @return mixed
     */
    public function fetch(array $roots);

    /**
     * Returns the data depending on the root.
     *
     * @param mixed $root
     * @param mixed $data
     *
     * @return mixed
     */
    public function pluck($root, $data);
}