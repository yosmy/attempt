<?php

namespace Yosmy;

/**
 * @di\service()
 */
class CollectAttempts
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
     *
     * @return Attempts
     */
    public function collect(
        string $action
    ): Attempts {
        $cursor = $this->manageCollection->find([
            'action' => $action,
        ]);

        return new Attempts($cursor);
    }
}