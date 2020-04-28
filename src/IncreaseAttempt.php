<?php

namespace Yosmy;

interface IncreaseAttempt
{
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
    );
}