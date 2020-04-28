<?php

namespace Yosmy;

/**
 * @di\service()
 */
class DeleteAttempt
{
    /**
     * @var ManageAttemptCollection
     */
    private $manageCollection;

    /**
     * @param ManageAttemptCollection $manageCollection
     */
    public function __construct(
        ManageAttemptCollection $manageCollection
    ) {
        $this->manageCollection = $manageCollection;
    }

    /**
     * @param string $action
     * @param string $subject
     */
    public function delete(
        string $action,
        string $subject
    ) {
        $this->manageCollection->deleteOne([
            'action' => $action,
            'subject' => $subject
        ]);
    }
}