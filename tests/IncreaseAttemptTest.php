<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;
use LogicException;

class IncreaseAttemptTest extends TestCase
{
    public function testIncreaseFirstTime()
    {
        $action = 'action';
        $subject = 'subject';
        $max = 3;
        $period = '1 hour';

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('findOne')
            ->with([
                'action' => $action,
                'subject' => $subject
            ])
            ->willReturn(false);

        $generateId = $this->createMock(Yosmy\GenerateId::class);

        $resolveTime = $this->createMock(Yosmy\ResolveTime::class);

        $increaseAttempt = new Yosmy\BaseIncreaseAttempt(
            $manageCollection,
            $generateId,
            $resolveTime
        );

        try {
            $increaseAttempt->increase(
                $action,
                $subject,
                $max,
                $period
            );
        } catch (Yosmy\ExceededAttemptException $e) {
            throw new LogicException();
        }
    }

    public function testIncreaseAfterThePeriod()
    {
        $action = 'action';
        $subject = 'subject';
        $max = 3;
        $period = '1 hour';

        $attempt = new Yosmy\BaseAttempt([
            '_id' => '',
            'from' => strtotime('2020-01-01 09:00:00')
        ]);

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('findOne')
            ->with([
                'action' => $action,
                'subject' => $subject,
            ])
            ->willReturn($attempt);

        $generateId = $this->createMock(Yosmy\GenerateId::class);

        $resolveTime = $this->createMock(Yosmy\ResolveTime::class);

        $now = strtotime('2020-01-01 10:30:00');

        $resolveTime->expects($this->once())
            ->method('resolve')
            ->with()
            ->willReturn($now);

        $manageCollection->expects($this->once())
            ->method('updateOne')
            ->with(
                [
                    '_id' => $attempt->getId()
                ],
                [
                    '$set' => [
                        'amount' => 1,
                        'from' => new Yosmy\Mongo\DateTime($now * 1000),
                    ]
                ]
            );

        $increaseAttempt = new Yosmy\BaseIncreaseAttempt(
            $manageCollection,
            $generateId,
            $resolveTime
        );

        try {
            $increaseAttempt->increase(
                $action,
                $subject,
                $max,
                $period
            );
        } catch (Yosmy\ExceededAttemptException $e) {
            throw new LogicException();
        }
    }

    /**
     * @throws Yosmy\ExceededAttemptException
     */
    public function testIncreaseHavingExceededAmount()
    {
        $action = 'action';
        $subject = 'subject';
        $max = 3;
        $period = '1 hour';

        $attempt = new Yosmy\BaseAttempt([
            '_id' => '',
            'amount' => 3,
            'from' => strtotime('2020-01-01 09:00:00')
        ]);

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('findOne')
            ->with([
                'action' => $action,
                'subject' => $subject,
            ])
            ->willReturn($attempt);

        $generateId = $this->createMock(Yosmy\GenerateId::class);

        $resolveTime = $this->createMock(Yosmy\ResolveTime::class);

        $resolveTime->expects($this->once())
            ->method('resolve')
            ->with()
            ->willReturn(strtotime('2020-01-01 09:30:00'));

        $this->expectException(Yosmy\ExceededAttemptException::class);

        $increaseAttempt = new Yosmy\BaseIncreaseAttempt(
            $manageCollection,
            $generateId,
            $resolveTime
        );

        try {
            $increaseAttempt->increase(
                $action,
                $subject,
                $max,
                $period
            );
        } catch (Yosmy\ExceededAttemptException $e) {
            throw $e;
        }
    }

    public function testIncreaseNotHavingExceededAmount()
    {
        $action = 'action';
        $subject = 'subject';
        $max = 3;
        $period = '1 hour';

        $attempt = new Yosmy\BaseAttempt([
            '_id' => '',
            'amount' => 2,
            'from' => strtotime('2020-01-01 09:00:00')
        ]);

        $manageCollection = $this->createMock(Yosmy\ManageAttemptCollection::class);

        $manageCollection->expects($this->once())
            ->method('findOne')
            ->with([
                'action' => $action,
                'subject' => $subject,
            ])
            ->willReturn($attempt);

        $generateId = $this->createMock(Yosmy\GenerateId::class);

        $resolveTime = $this->createMock(Yosmy\ResolveTime::class);

        $now = strtotime('2020-01-01 09:30:00');

        $resolveTime->expects($this->once())
            ->method('resolve')
            ->with()
            ->willReturn($now);

        $manageCollection->expects($this->once())
            ->method('updateOne')
            ->with(
                [
                    '_id' => $attempt->getId(),
                ],
                [
                    '$inc' => [
                        'amount' => 1
                    ],
                    '$set' => [
                        'from' => new Yosmy\Mongo\DateTime($now * 1000),
                    ]
                ]
            )
            ->willReturn($attempt);

        $increaseAttempt = new Yosmy\BaseIncreaseAttempt(
            $manageCollection,
            $generateId,
            $resolveTime
        );

        try {
            $increaseAttempt->increase(
                $action,
                $subject,
                $max,
                $period
            );
        } catch (Yosmy\ExceededAttemptException $e) {
            throw new LogicException();
        }
    }
}