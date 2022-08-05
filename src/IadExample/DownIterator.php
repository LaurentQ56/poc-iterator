<?php

namespace POCIterator\IadExample;

final class DownIterator implements \Iterator
{
    private PeopleCollection $collection;
    private int $level = 0;
    private int $left = 1;
    private int $position = 0;

    public function __construct(PeopleCollection $collection)
    {
        $this->collection = $collection;
    }

    public function current(): People
    {
        return $this->collection->getItem($this->level, $this->left);
    }

    public function next(): void
    {
        $current = $this->current();
        $siblings = $this->collection->getByLevel($current->getLevel());
        if ($siblings[count($siblings) -1]->getLeft() === $this->left) {
            $this->left = 1;
            $this->level++;
        } else {
            $this->left = $current->getLeft() + 1;
        }
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->collection->leftIsValid($this->level, $this->left);
    }

    public function rewind(): int
    {
        // TODO: Implement rewind() method.
    }
}
