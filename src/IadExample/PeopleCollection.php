<?php

namespace POCIterator\IadExample;

final class PeopleCollection implements \IteratorAggregate
{
    /** @var People[] */
    private array $items = [];

    public function addItem(People $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return People[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getIterator(): \Iterator
    {
        // TODO: Implement getIterator() method.
    }

    public function getIteratorDown(): \Iterator
    {
        return new DownIterator($this);
    }

    /**
     * @return People[]
     */
    public function getByLevel(int $level): array
    {
        $levelItems = array_map(static function (People $people) use ($level) {
            return $people->getLevel() === $level;
        }, $this->items);

        usort($levelItems, static function (People $itemA, People $itemB) {
            return $itemA->getLeft() - $itemB->getLeft();
        });

        return $levelItems;
    }

    public function getItem(int $level, int $left): People
    {
        return array_reduce($this->items, static function (People $people) use ($level, $left) {
            return $people->getLevel() === $level && $people->getLeft() === $left;
        });
    }

    public function levelIsValid(int $level): bool
    {
        foreach ($this->items as $item) {
            $return = $item->getLevel() === $level;
            if (true === $return) {
                return true;
            }
        }

        return false;
    }

    public function leftIsValid(int $level, int $left): bool
    {
        if (true === $this->levelIsValid($level)) {
            foreach ($this->items as $item) {
                $return = $item->getLeft() === $left;
                if (true === $return) {
                    return true;
                }
            }
        }

        return false;
    }
}
