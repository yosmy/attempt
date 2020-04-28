<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;

class DeleteAttemptTest extends TestCase
{
    public function testDelete()
    {
        $action = 'action';
        $subject = 'subject';

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('deleteOne')
            ->with([
                'action' => $action,
                'subject' => $subject
            ]);

        $deleteAttempt = new Yosmy\DeleteAttempt(
            $manageCollection
        );

        $deleteAttempt->delete(
            $action,
            $subject
        );
    }
}