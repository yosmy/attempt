<?php

namespace Yosmy;

use Yosmy\Mongo;

class Attempts extends Mongo\Collection
{
    /**
     * @var Attempt[]
     */
    protected $cursor;
}
