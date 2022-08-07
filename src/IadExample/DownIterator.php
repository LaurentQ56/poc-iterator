<?php

declare(strict_types=1);

namespace POCIterator\IadExample;

final class DownIterator implements \Iterator
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
        $current = $this->current();
        $parent = $current->parent();
        $this->position++;
        $this->left++;

        if (0 < count($current->children())) {
            $this->level++;
        } elseif (null !== $parent && 0 < count($parent->children())) {
            $last = count($parent->children()) - 1;
            $lastSibling = $parent->children()[$last];
            $grandParent = $parent->parent();

            if ($lastSibling->left() < $this->left) {
                $this->level--;

                if (null !== $grandParent && 0 < count($grandParent->children())) {
                    $lastParentChildren = count($grandParent->children()) - 1;
                    $lastParentSibling = $grandParent->children()[$lastParentChildren];

                    if ($lastParentSibling->left() < $this->left) {
                        $this->level--;
                    }
                }
            }
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
        $current = $this->current();
        $this->left--;

        if (null !== $current->parent()) {
            $this->level--;
        }

        $this->position--;
    }
}
