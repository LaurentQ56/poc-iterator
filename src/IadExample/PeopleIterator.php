<?php

declare(strict_types=1);

namespace POCIterator\IadExample;

final class PeopleIterator implements \Iterator
{
    private PeopleCollection $collection;
    private int $level = 1;
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
        $siblings = $this->collection->getByLevel($this->level);
        $this->left++;
        $this->position++;
        foreach ($siblings as $key => $people) {
            if (array_key_exists($key + 1, $siblings) && $people->left() === $this->left - 1) {
                $this->left = $siblings[$key + 1]->left();
                break;
            }
        }
        if (array_reverse($siblings)[0]->left() === $this->left - 1) {
            $this->level++;
            $siblingsLevelUp = $this->collection->getByLevel($this->level);
            $this->left = $siblingsLevelUp[0]->left();
        }
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->collection->leftIsValid($this->level, $this->left);
    }

    public function rewind(): void
    {
        $siblings = $this->collection->getByLevel($this->level);
        $this->left--;
        $this->position--;
        foreach ($siblings as $key => $people) {
            if (array_key_exists($key - 1, $siblings) && $people->left() === $this->left + 1) {
                $this->left = $siblings[$key - 1]->left();
                break;
            }
        }
        if ($siblings[0]->left() === $this->left + 1) {
            $this->level--;
            $siblingsLevelUp = $this->collection->getByLevel($this->level);
            $this->left = array_reverse($siblingsLevelUp)[0]->left();
        }
    }
}
