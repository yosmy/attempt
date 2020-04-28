<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;

class ManageAttemptCollectionTest extends TestCase
{
    public function testGet()
    {
        $manageCollection = new Yosmy\BaseManageAttemptCollection(
            'mongodb://mongo:27017',
            'db',
            'attempts',
            AttemptTest::class
        );

        $this->assertEquals(
            'attempts',
            $manageCollection->getName()
        );
    }
}