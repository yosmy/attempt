<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;

class AttemptTest extends TestCase
{
    public function testGetters()
    {
        $id = 'id';
        $action = 'action';
        $subject = 'subject';
        $amount = 2;
        $from = 1234567890;

        $attempt = new Yosmy\BaseAttempt([
            '_id' => $id,
            'action' => $action,
            'subject' => $subject,
            'amount' => $amount,
            'from' => $from,
        ]);

        $this->assertEquals(
            $id,
            $attempt->getId()
        );

        $this->assertEquals(
            $action,
            $attempt->getAction()
        );

        $this->assertEquals(
            $subject,
            $attempt->getSubject()
        );

        $this->assertEquals(
            $amount,
            $attempt->getAmount()
        );

        $this->assertEquals(
            $from,
            $attempt->getFrom()
        );
    }

    public function testBsonUnserialize()
    {
        $id = 'id';
        $action = 'action';
        $subject = 'subject';
        $amount = 2;
        $from = 1234567890;

        $attempt = new Yosmy\BaseAttempt();

        $attempt->bsonUnserialize([
            '_id' => $id,
            'action' => $action,
            'subject' => $subject,
            'amount' => $amount,
            'from' => new Yosmy\Mongo\DateTime($from * 1000),
        ]);

        $this->assertEquals(
            $id,
            $attempt->getId()
        );

        $this->assertEquals(
            $from,
            $attempt->getFrom()
        );
    }
}