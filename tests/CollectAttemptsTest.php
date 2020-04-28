<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;

class CollectAttemptsTest extends TestCase
{
    public function testCollect()
    {
        $action = 'action';

        $collection = $this->createMock(Yosmy\Mongo\Collection::class);

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('find')
            ->with([
                'action' => $action,
            ])
            ->willReturn($collection);

        $collectAttempts = new Yosmy\CollectAttempts(
            $manageCollection
        );

        $attempts = $collectAttempts->collect(
            $action
        );

        $this->assertEquals(
            new Yosmy\Attempts($collection),
            $attempts
        );
    }
}