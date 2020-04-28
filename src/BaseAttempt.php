<?php

namespace Yosmy;

use Yosmy\Mongo;

class BaseAttempt extends Mongo\Document implements Attempt
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->offsetGet('_id');
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->offsetGet('action');
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->offsetGet('subject');
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->offsetGet('amount');
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function bsonUnserialize(array $data)
    {
        /** @var Mongo\DateTime $from */
        $from = $data['from'];
        $data['from'] = $from->toDateTime()->getTimestamp();

        parent::bsonUnserialize($data);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): object
    {
        $data = parent::jsonSerialize();

        $data->id = $data->_id;

        unset($data->_id);

        return $data;
    }
}