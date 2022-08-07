<?php

declare(strict_types=1);

namespace POCIterator\IadExample;

final class PeopleCollection implements \IteratorAggregate
{
    /** @var People[] */
    private array $items = [];
    private int $left = 0;

    public function addItem(People $item): self
    {
        $this->left = $item->updateLeft(++$this->left);
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
        return new PeopleIterator($this);
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
        $levelItems = array_filter($this->buildFlatArray(), static function (People $people) use ($level) {
            return $people->level() === $level;
        });

        usort($levelItems, static function (People $peopleA, People $peopleB) {
            return $peopleA->left() - $peopleB->left();
        });

        return $levelItems;
    }

    /**
     * @return People[]
     */
    public function getByLeft(int $left): array
    {
        $leftItems = array_filter($this->items, static function (People $people) use ($left) {
            return $people->left() === $left;
        });

        usort($leftItems, static function (People $peopleA, People $peopleB) {
            return $peopleA->level() - $peopleB->level();
        });

        return $leftItems;
    }

    public function getItem(int $level, int $left): People
    {
        $item = array_filter($this->buildFlatArray(), static function (People $people) use ($level, $left) {
            return $people->level() === $level && $people->left() === $left;
        });

        return array_values($item)[0];
    }

    public function getTree(): array
    {
        $tree = [];
        foreach ($this->items as $keyTree => $item) {
            $tree[$keyTree]['username'] = $item->username();
            $tree[$keyTree]['slug'] = $item->slug();
            if (0 < count($item->children())) {
                $tree[$keyTree]['children'] = $this->getChildrenTree($item->children());
            }
        }

        return $tree;
    }

    public function levelIsValid(int $level): bool
    {
        foreach ($this->items as $item) {
            $return = $item->level() === $level;
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
                $return = $item->left() === $left;
                if (true === $return) {
                    return true;
                }
            }
        }

        return false;
    }

    private function buildFlatArray(): array
    {
        $planArray = [];
        foreach ($this->items as $item) {
            if (0 < count($item->children())) {
                $planArray = array_reverse($this->getChildren($item->children(), $planArray));
            }
            $planArray[] = $item;
        }

        return $planArray;
    }

    /**
     * @param People[] $children
     */
    private function getChildren(array $children, array $globalArray): array
    {
        foreach ($children as $child) {
            if (0 < count($child->children())) {
                $globalArray = $this->getChildren($child->children(), $globalArray);
            }

            $globalArray[] = $child;
        }

        return array_reverse($globalArray);
    }

    /**
     * @param People[] $children
     */
    public function getChildrenTree(array $children): array
    {
        $tree = [];
        foreach ($children as $keyChild => $child) {
            $tree[$keyChild]['username'] = $child->username();
            $tree[$keyChild]['slug'] = $child->slug();
            if (0 < count($child->children())) {
                $tree[$keyChild]['children'] = $this->getChildrenTree($child->children());
            }
        }

        return $tree;
    }
}
