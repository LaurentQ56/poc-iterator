<?php

declare(strict_types=1);

namespace POCIterator\IadExample;

final class People
{
    private string $username;
    private int $left;
    private int $level;
    private ?People $parent;
    private array $children = [];
    private string $slug;

    public function __construct(string $username, int $left, ?People $parent = null)
    {
        $this->username = $username;
        $this->left = $left;
        $this->level = 1;
        $this->slug = strtolower($username);
        $this->parent = $parent;

        if (null !== $parent) {
            $this->slug = sprintf('%s-%s', strtolower($parent->slug), strtolower($username));
            $parent->addChildren($this);
            $this->level += $parent->level();
            $this->left = $parent->left + count($parent->children());
        }
    }

    public static function create(string $username, ?int $left = 1, ?People $parent = null): self
    {
        return new self($username, $left, $parent);
    }

    public function username(): string
    {
        return $this->username;
    }

    public function updateLeft(int $left): int
    {
        $this->left = $left;
        foreach ($this->children() as $child) {
            $child->left = ++$left;
            if (0 < count($child->children)) {
                $left = $this->updateLeftChildren($child->children(), $left);
            }
        }

        return $left;
    }

    public function left(): int
    {
        return $this->left;
    }

    public function level(): int
    {
        return $this->level;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function parent(): ?People
    {
        return $this->parent;
    }

    /**
     * @return People[]
     */
    public function children(): array
    {
        return $this->children;
    }

    private function addChildren(People $child): void
    {
        $this->children[] = $child;
    }

    private function updateLeftChildren(array $children, int $left): int
    {
        foreach ($children as $child) {
            $child->left = ++$left;
            if (0 < count($child->children)) {
                $this->updateLeftChildren($child->children(), $left);
            }
        }

        return $left;
    }
}
