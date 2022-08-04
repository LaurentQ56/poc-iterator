<?php

declare(strict_types=1);

namespace POCIterator;

final class WordsCollection implements \IteratorAggregate
{
    private array $items = [];

    public function addItem(string $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getIterator(): \Iterator
    {
        return new WordIterator($this);
    }

    public function getIteratorReverse(): \Iterator
    {
        return new WordIterator($this, true);
    }
}
