<?php

declare(strict_types=1);

namespace POCIterator\IadExample;

final class People
{
    private string $username;
    private int $left;
    private int $level;
    private ?People $parent;
    private array $children;

    public function __construct(string $username, ?int $left = 1, ?People $parent = null)
    {
        $this->username = $username;
        $this->left = $left;
        $this->level = 0;
        $this->parent = $parent;
        if (null !== $parent) {
            $parent->addChildren($this);
            $this->level = $parent->getLevel() + 1;
            $this->left = count($parent->getChildren()) + 1;
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getLeft(): int
    {
        return $this->left;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getParent(): ?People
    {
        return $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    private function addChildren(People $child): self
    {
        $this->children[] = $child;

        return $this;
    }
}
