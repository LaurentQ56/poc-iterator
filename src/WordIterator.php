<?php

declare(strict_types=1);

namespace POCIterator;

final class WordIterator implements \Iterator
{
    private WordsCollection $collection;
    private bool $reverse;
    private int $position = 0;

    public function __construct(WordsCollection $collection, bool $reverse = false)
    {
        $this->collection = $collection;
        $this->reverse = $reverse;
    }

    public function current(): string
    {
        return $this->collection->getItems()[$this->position];
    }

   public function next(): void
    {
        $this->position += ($this->reverse ? -1 : 1);
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->collection->getItems()[$this->position]);
    }

    public function rewind(): void
    {
        $this->position -= ($this->reverse ? -1 : 1);
    }
}
