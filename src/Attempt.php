<?php

namespace Yosmy;

interface Attempt
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getAction(): string;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @return int
     */
    public function getAmount(): int;

    /**
     * @return int
     */
    public function getFrom(): int;
}