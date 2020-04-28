<?php

namespace Yosmy;

use Yosmy\Mongo;

/**
 * @di\service()
 */
class BaseIncreaseAttempt
{
    /**
     * @var ManageAttemptCollection
     */
    private $manageCollection;

    /**
     * @var GenerateId
     */
    private $generateId;

    /**
     * @var ResolveTime
     */
    private $resolveTime;

    /**
     * @param ManageAttemptCollection $manageCollection
     * @param GenerateId              $generateId
     * @param ResolveTime             $resolveTime
     */
    public function __construct(
        ManageAttemptCollection $manageCollection,
        GenerateId $generateId,
        ResolveTime $resolveTime
    ) {
        $this->manageCollection = $manageCollection;
        $this->generateId = $generateId;
        $this->resolveTime = $resolveTime;
    }

    /**
     * @param string $action
     * @param string $subject
     * @param int    $max
     * @param string $period
     *
     * @throws ExceededAttemptException
     */
    public function increase(
        string $action,
        string $subject,
        int $max,
        string $period
    ) {
        /* First time */

        /** @var Attempt $attempt */
        $attempt = $this->manageCollection->findOne([
            'action' => $action,
            'subject' => $subject
        ]);

        if (!$attempt) {
            $this->manageCollection->insertOne([
                '_id' => $this->generateId->generate(),
                'action' => $action,
                'subject' => $subject,
                'amount' => 1,
                'from' => new Mongo\DateTime($this->resolveTime->resolve() * 1000)
            ]);

            return;
        }

        /* After the period */

        // From date plus period
        $until = strtotime(sprintf(
            '%s +%s',
            date('Y-m-d H:i:s', $attempt->getFrom()),
            $period
        ));

        $now = $this->resolveTime->resolve();

        // After the period?
        if ($until < $now) {
            $this->manageCollection->updateOne(
                [
                    '_id' => $attempt->getId()
                ],
                [
                    '$set' => [
                        'amount' => 1,
                        'from' => new Mongo\DateTime($now * 1000),
                    ]
                ]
            );

            return;
        }

        /* Exceeded within the period */

        if ($attempt->getAmount() == $max) {
            throw new BaseExceededAttemptException();
        }

        /* Not exceeded within the period */

        $this->manageCollection->updateOne(
            [
                '_id' => $attempt->getId(),
            ],
            [
                '$inc' => [
                    'amount' => 1
                ],
                '$set' => [
                    'from' => new Mongo\DateTime($now * 1000),
                ]
            ]
        );
    }
}